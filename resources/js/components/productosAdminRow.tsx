import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Select from 'react-select';

export default function ProductosAdminRow({ producto }) {
    const [edit, setEdit] = useState(false);

    const { lineas, ambientes, usos, espacios, colores } = usePage().props;
    const normalizedAllAmbientes = Array.from(
        new Map(
            [
                ...((lineas || []).flatMap((linea) => linea?.ambientes || [])),
                ...(ambientes || []),
            ].map((ambiente) => [ambiente?.id, ambiente]),
        ).values(),
    );
    const initialAmbientes =
        (producto?.ambientes?.length
            ? producto.ambientes
            : normalizedAllAmbientes?.filter((ambiente) => ambiente.id == producto?.ambiente_id))?.map((ambiente) => ({
            value: ambiente.id,
            label: ambiente.name_es,
        })) || [];
    const initialColores = producto?.colores?.map((color) => ({
        value: color.id,
        label: color.name,
        hex: color.hex,
    })) || [];
    const selectMenuProps = {
        menuPortalTarget: typeof window !== 'undefined' ? document.body : undefined,
        menuPosition: 'fixed' as const,
        styles: {
            menuPortal: (base) => ({
                ...base,
                zIndex: 9999,
            }),
        },
    };

    console.log(producto);

    const { data, setData, post, errors } = useForm({
        order: producto?.order,
        name: producto?.name,
        lampara: producto?.lampara,
        origen: producto?.origen,
        code: producto?.code,
        espacio_id: producto?.espacio_id,
        uso_id: producto?.uso_id,
        linea_id: producto?.linea_id,
        medidas: producto?.medidas,
        id: producto?.id,
        ambientes: producto?.ambientes?.map((ambiente) => ambiente.id) || [],
        colores: producto?.colores?.map((color) => color.id) || [],
        instructivo: null,
        certificado: null,
        new_images: [],
        images_to_delete: [],
    });
    const allAmbientes =
        normalizedAllAmbientes?.map((ambiente) => ({
            value: ambiente.id,
            label: ambiente.name_es,
        })) || [];
    const availableAmbientes = [
        ...allAmbientes,
        ...initialAmbientes.filter(
            (selected) => !allAmbientes.some((ambiente) => ambiente.value === selected.value),
        ),
    ];

    const resetFormState = () => {
        setData({
            order: producto?.order ?? '',
            name: producto?.name ?? '',
            lampara: producto?.lampara ?? '',
            origen: producto?.origen ?? '',
            code: producto?.code ?? '',
            espacio_id: producto?.espacio_id ?? '',
            uso_id: producto?.uso_id ?? '',
            linea_id: producto?.linea_id ?? '',
            medidas: producto?.medidas ?? '',
            id: producto?.id,
            ambientes: producto?.ambientes?.map((ambiente) => ambiente.id) || [],
            colores: producto?.colores?.map((color) => color.id) || [],
            instructivo: null,
            certificado: null,
            new_images: [],
            images_to_delete: [],
        });
        setExistingImages(producto?.imagenes || []);
        setNewImagePreviews([]);
        setImagesToDelete([]);
        setAmbienteSelected(initialAmbientes);
        setColorSelected(initialColores);
    };

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('admin.productos.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Producto actualizada correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar producto');
                console.log(errors);
            },
        });
    };

    const deleteMarca = () => {
        if (confirm('¿Estas seguro de eliminar este producto?')) {
            post(route('admin.productos.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Producto eliminada correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar producto');
                    console.log(errors);
                },
            });
        }
    };

    const [existingImages, setExistingImages] = useState(producto.imagenes || []);
    const [newImagePreviews, setNewImagePreviews] = useState([]);
    const [imagesToDelete, setImagesToDelete] = useState([]);
    const [ambienteSelected, setAmbienteSelected] = useState(initialAmbientes);
    const [colorSelected, setColorSelected] = useState(initialColores);

    useEffect(() => {
        setData(
            'colores',
            (colorSelected || []).map((a) => a.value),
        );
    }, [colorSelected]);

    useEffect(() => {
        setData(
            'ambientes',
            (ambienteSelected || []).map((a) => a.value),
        );
    }, [ambienteSelected]);

    useEffect(() => {
        if (!edit) {
            return;
        }

        resetFormState();
    }, [edit, producto?.id]);

    const handleFileChange = (e) => {
        const files = Array.from(e.target.files);

        // Actualizar el form data con los archivos nuevos
        setData('new_images', files);

        // Crear previews de las nuevas imágenes
        const previews = files.map((file) => {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) =>
                    resolve({
                        file: file,
                        url: e.target.result,
                        name: file.name,
                        size: file.size,
                        isNew: true,
                    });
                reader.readAsDataURL(file);
            });
        });

        Promise.all(previews).then(setNewImagePreviews);
    };

    const removeExistingImage = (indexToRemove) => {
        const imageToDelete = existingImages[indexToRemove];

        // Agregar al array de imágenes a eliminar
        setImagesToDelete((prev) => [...prev, imageToDelete.id]);

        // Remover de imágenes existentes
        const newExistingImages = existingImages.filter((_, index) => index !== indexToRemove);
        setExistingImages(newExistingImages);

        // Actualizar form data
        setData('images_to_delete', [...imagesToDelete, imageToDelete.id]);
    };

    const removeNewImage = (indexToRemove) => {
        const newImages = data.new_images?.filter((_, index) => index !== indexToRemove) || [];
        const newPreviews = newImagePreviews.filter((_, index) => index !== indexToRemove);

        setData('new_images', newImages);
        setNewImagePreviews(newPreviews);
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="h-[90px] align-middle">{producto?.order}</td>
            <td className="align-middle">{producto?.code}</td>
            <td className="align-middle">{producto?.name}</td>
            <td className="align-middle">{producto?.espacio?.name_es}</td>
            <td className="align-middle">{producto?.uso?.name_es}</td>
            <td className="align-middle">{producto?.linea?.name_es}</td>
            <td className="align-middle">
                {producto?.ambientes?.map((ambiente) => (
                    <span key={ambiente.id} className="mr-1 mb-1 inline-block rounded bg-blue-100 px-2 py-1 text-xs text-blue-800">
                        {ambiente.name_es}
                    </span>
                ))}
            </td>

            <td className="w-[140px] text-center">
                <div className="flex flex-row justify-center gap-3">
                    <button onClick={() => setEdit(true)} className="h-10 w-10 rounded-md border border-blue-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faPen} size="lg" color="#3b82f6" />
                    </button>
                    <button onClick={deleteMarca} className="h-10 w-10 rounded-md border border-red-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faTrash} size="lg" color="#fb2c36" />
                    </button>
                </div>
            </td>

            <AnimatePresence>
                {edit && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                    >
                        <form onSubmit={handleUpdate} method="POST" className="relative rounded-lg bg-white text-black">
                            <div className="bg-primary-orange sticky top-0 flex flex-row items-center gap-2 rounded-t-lg p-4">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="28"
                                    height="28"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="#ffffff"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    className="lucide lucide-pen-icon lucide-pen"
                                >
                                    <path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z" />
                                </svg>
                                <h2 className="text-2xl font-semibold text-white">Actualizar Producto</h2>
                            </div>

                            <div className="max-h-[60vh] w-[500px] overflow-y-auto rounded-md bg-white p-4">
                                <div className="flex flex-col gap-4">
                                    <label htmlFor="ordennn">Orden</label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="ordennn"
                                        id="ordennn"
                                        value={data.order}
                                        onChange={(e) => setData('order', e.target.value)}
                                    />
                                    <label htmlFor="nombree">
                                        Nombre <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="nombree"
                                        id="nombree"
                                        value={data.name}
                                        onChange={(e) => setData('name', e.target.value)}
                                    />

                                    <label htmlFor="code">
                                        Codigo <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="code"
                                        id="code"
                                        value={data.code}
                                        onChange={(e) => setData('code', e.target.value)}
                                    />

                                    <label htmlFor="code_oem">Medidas</label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="code_oem"
                                        id="code_oem"
                                        value={data.medidas}
                                        onChange={(e) => setData('medidas', e.target.value)}
                                    />

                                    <label htmlFor="origen">Origen</label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="origen"
                                        id="origen"
                                        value={data.origen}
                                        onChange={(e) => setData('origen', e.target.value)}
                                    />

                                    <label htmlFor="lampara">Lampara</label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="lampara"
                                        id="lampara"
                                        value={data.lampara}
                                        onChange={(e) => setData('lampara', e.target.value)}
                                    />

                                    <label htmlFor="color">
                                        Colores <span className="text-red-500">*</span>
                                    </label>
                                    <Select
                                        options={colores?.map((color) => ({
                                            value: color.id,
                                            label: color.name,
                                            hex: color.hex, // Incluimos el hex en la opción
                                        }))}
                                        value={colorSelected}
                                        onChange={(options) => setColorSelected(options || [])}
                                        className=""
                                        name="color"
                                        id="color"
                                        isMulti
                                        {...selectMenuProps}
                                        formatOptionLabel={(option) => (
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                <div
                                                    style={{
                                                        width: '16px',
                                                        height: '16px',
                                                        backgroundColor: option.hex,
                                                        border: '1px solid #ccc',
                                                        borderRadius: '2px',
                                                    }}
                                                />
                                                <span>{option.label}</span>
                                            </div>
                                        )}
                                    />

                                    <label htmlFor="categoria">
                                        Espacios <span className="text-red-500">*</span>
                                    </label>
                                    <select
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        value={data.espacio_id || ''}
                                        onChange={(e) => setData('espacio_id', e.target.value)}
                                        name=""
                                        id=""
                                    >
                                        <option value="">Seleccionar Espacio</option>
                                        {espacios.map((espacio) => (
                                            <option className="text-black" key={espacio.id} value={espacio.id}>
                                                {espacio.name_es}
                                            </option>
                                        ))}
                                    </select>

                                    <label htmlFor="categoria">
                                        Usos <span className="text-red-500">*</span>
                                    </label>
                                    <select
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        value={data.uso_id || ''}
                                        onChange={(e) => setData('uso_id', e.target.value)}
                                        name=""
                                        id=""
                                    >
                                        <option value="">Seleccionar uso</option>
                                        {usos
                                            ?.filter((uso) => uso.espacio_id == data.espacio_id)
                                            ?.map((uso) => (
                                                <option key={uso.id} value={uso.id}>
                                                    {uso.name_es}
                                                </option>
                                            ))}
                                    </select>

                                    <label htmlFor="subcategoria">
                                        Lineas <span className="text-red-500">*</span>
                                    </label>
                                    <select
                                        value={data.linea_id || ''}
                                        onChange={(e) => setData('linea_id', e.target.value)}
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        name="lineas"
                                        id="lineas"
                                    >
                                        <option value="">Seleccionar linea</option>
                                        {lineas.map((linea) => (
                                            <option key={linea.id} value={linea.id}>
                                                {linea.name_es}
                                            </option>
                                        ))}
                                    </select>

                                    <label htmlFor="subcategoria">
                                        Ambientes <span className="text-red-500">*</span>
                                    </label>
                                    <Select
                                        options={availableAmbientes}
                                        value={ambienteSelected}
                                        onChange={(options) => setAmbienteSelected(options || [])}
                                        className=""
                                        name="subcategoria"
                                        id="subcategoria"
                                        isMulti
                                        hideSelectedOptions={false}
                                        closeMenuOnSelect={false}
                                        {...selectMenuProps}
                                    />

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="instructivo">Instructivo </label>
                                        <input
                                            className="file:bg-primary-orange focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 file:cursor-pointer file:rounded-sm file:px-2 file:py-1 file:text-white focus:outline"
                                            type="file"
                                            name="archivo_fotos"
                                            id="archivo_fotos"
                                            onChange={(e) => setData('instructivo', e.target.files[0])}
                                        />
                                    </div>

                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="certificado">Certificado </label>
                                        <input
                                            className="file:bg-primary-orange focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 file:cursor-pointer file:rounded-sm file:px-2 file:py-1 file:text-white focus:outline"
                                            type="file"
                                            name="certificado"
                                            id="certificado"
                                            onChange={(e) => setData('certificado', e.target.files[0])}
                                        />
                                    </div>

                                    <label>Imágenes del Producto</label>
                                    <input
                                        type="file"
                                        multiple
                                        accept="image/*"
                                        onChange={handleFileChange}
                                        className="file:bg-primary-orange w-full rounded border p-2 file:cursor-pointer file:rounded-full file:px-4 file:py-2 file:text-white"
                                    />
                                    {errors.new_images && <span className="text-red-500">{errors.new_images}</span>}
                                    {errors['new_images.*'] && <span className="text-red-500">{errors['new_images.*']}</span>}

                                    {/* Mostrar imágenes existentes */}
                                    {existingImages.length > 0 && (
                                        <div className="mt-4 space-y-2">
                                            <h4>Imágenes actuales ({existingImages.length})</h4>
                                            <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
                                                {existingImages.map((image, index) => (
                                                    <div key={image.id} className="relative">
                                                        <img
                                                            src={image.image || image.path} // Ajusta según tu estructura
                                                            alt={image.name || `Imagen ${index + 1}`}
                                                            className="h-32 w-full rounded border object-cover"
                                                        />
                                                        <button
                                                            type="button"
                                                            onClick={() => removeExistingImage(index)}
                                                            className="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-sm text-white hover:bg-red-600"
                                                        >
                                                            ×
                                                        </button>
                                                        <p className="mt-1 truncate text-xs text-gray-600">{image.name || `Imagen ${index + 1}`}</p>
                                                        <span className="inline-block rounded bg-blue-100 px-2 py-1 text-xs text-blue-800">
                                                            Existente
                                                        </span>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Mostrar nuevas imágenes */}
                                    {newImagePreviews.length > 0 && (
                                        <div className="mt-4 space-y-2">
                                            <h4>Nuevas imágenes ({newImagePreviews.length})</h4>
                                            <div className="grid grid-cols-2 gap-4 md:grid-cols-4">
                                                {newImagePreviews.map((preview, index) => (
                                                    <div key={index} className="relative">
                                                        <img
                                                            src={preview.url}
                                                            alt={preview.name}
                                                            className="h-32 w-full rounded border object-cover"
                                                        />
                                                        <button
                                                            type="button"
                                                            onClick={() => removeNewImage(index)}
                                                            className="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-sm text-white hover:bg-red-600"
                                                        >
                                                            ×
                                                        </button>
                                                        <p className="mt-1 truncate text-xs text-gray-600">{preview.name}</p>
                                                        <p className="text-xs text-gray-500">{(preview.size / 1024 / 1024).toFixed(2)} MB</p>
                                                        <span className="inline-block rounded bg-green-100 px-2 py-1 text-xs text-green-800">
                                                            Nueva
                                                        </span>
                                                    </div>
                                                ))}
                                            </div>
                                        </div>
                                    )}

                                    {/* Mostrar total de imágenes */}
                                    <div className="mt-2 text-sm text-gray-600">
                                        Total de imágenes: {existingImages.length + newImagePreviews.length}
                                    </div>
                                </div>
                            </div>

                            <div className="bg-primary-orange sticky bottom-0 flex justify-end gap-4 rounded-b-md p-4">
                                <button
                                    type="button"
                                    onClick={() => setEdit(false)}
                                    className="rounded-md border border-red-500 bg-red-500 px-2 py-1 text-white transition duration-300"
                                >
                                    Cancelar
                                </button>
                                <button
                                    type="submit"
                                    className="hover:text-primary-orange rounded-md px-2 py-1 text-white outline outline-white transition duration-300 hover:bg-white"
                                >
                                    Actualizar
                                </button>
                            </div>
                        </form>
                    </motion.div>
                )}
            </AnimatePresence>
        </tr>
    );
}

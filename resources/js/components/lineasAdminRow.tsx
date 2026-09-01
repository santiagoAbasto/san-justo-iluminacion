import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Select from 'react-select';
import CustomReactQuill from './CustomReactQuill';
import Switch from './Switch';

export default function LineasAdminRow({ linea }) {
    const [edit, setEdit] = useState(false);

    const { ambientes } = usePage().props;
    const initialAmbientes = linea?.ambientes?.map((ambiente) => ({
        value: ambiente.id,
        label: ambiente.name_es,
    })) || [];

    const updateForm = useForm({
        name_es: linea?.name_es,
        name_en: linea?.name_en,
        order: linea?.order,
        text_es: linea?.text_es,
        text_en: linea?.text_en,
        id: linea?.id,
        ambientes: linea?.ambientes?.map((ambiente) => ambiente.id) || [],
    });

    const [text_es, setTextEs] = useState(linea?.text_es);
    const [text_en, setTextEn] = useState(linea?.text_en);
    const [ambienteSelected, setAmbienteSelected] = useState(initialAmbientes);

    useEffect(() => {
        updateForm.setData(
            'ambientes',
            (ambienteSelected || []).map((a) => a.value),
        );
    }, [ambienteSelected]);

    useEffect(() => {
        updateForm.setData('text_es', text_es);
    }, [text_es]);

    useEffect(() => {
        updateForm.setData('text_en', text_en);
    }, [text_en]);

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        updateForm.post(route('admin.lineas.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Linea actualizada correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar linea');
                console.log(errors);
            },
        });
    };

    const deleteLinea = () => {
        if (confirm('è¢ƒEstas seguro de eliminar esta linea?')) {
            updateForm.delete(route('admin.lineas.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Linea eliminada correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar linea');
                    console.log(errors);
                },
            });
        }
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="align-middle">{linea?.order}</td>
            <td className="h-[90px] align-middle">{linea?.name_es}</td>
            <td className="max-w-[100px]">
                <div className="line-clamp-3 overflow-hidden break-words" dangerouslySetInnerHTML={{ __html: linea?.text_es }} />
            </td>
            <td className="">
                {linea?.ambientes?.map((ambiente) => (
                    <span key={ambiente.id} className="bg-primary-orange mx-1 inline-block rounded px-2 py-1 text-xs font-semibold text-white">
                        {ambiente.name_es}
                    </span>
                ))}
            </td>

            <td className="h-[90px] max-w-[100px] align-middle">
                {linea?.image ? (
                    <img className="h-full w-full object-cover" src={`/storage/${linea.image}`} alt={linea?.name_es || 'Linea'} />
                ) : (
                    <div className="flex h-full w-full items-center justify-center bg-gray-100 text-xs text-gray-500">Sin imagen</div>
                )}
            </td>

            <td>
                <Switch id={linea?.id} routeName="lineas.changeDestacado" status={linea?.destacado == 1} />
            </td>

            <td className="w-[140px] text-center">
                <div className="flex flex-row justify-center gap-3">
                    <button onClick={() => setEdit(true)} className="h-10 w-10 rounded-md border border-blue-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faPen} size="lg" color="#3b82f6" />
                    </button>
                    <button type="button" onClick={deleteLinea} className="h-10 w-10 rounded-md border border-red-500 px-2 py-1 text-white">
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
                        <form onSubmit={handleUpdate} method="POST" className="max-h-[90vh] overflow-y-auto text-black">
                            <div className="w-[800px] rounded-md bg-white p-4">
                                <h2 className="mb-4 text-2xl font-semibold">Actualizar Linea</h2>
                                <div className="flex flex-col gap-4">
                                    <label htmlFor="ordennn">Orden</label>
                                    <input
                                        className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                        type="text"
                                        name="ordennn"
                                        id="ordennn"
                                        value={updateForm?.data?.order}
                                        onChange={(e) => updateForm.setData('order', e.target.value)}
                                    />
                                    <div className="flex w-full flex-row gap-5">
                                        <div className="flex w-full flex-col gap-3">
                                            <label htmlFor="nombree_es">
                                                Nombre {'(Espaè´–ol)'} <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                                type="text"
                                                name="nombree_es"
                                                id="nombree_es"
                                                value={updateForm?.data?.name_es}
                                                onChange={(e) => updateForm.setData('name_es', e.target.value)}
                                            />
                                        </div>
                                        <div className="flex w-full flex-col gap-3">
                                            <label htmlFor="nombree_en">
                                                Nombre {'(Ingles)'} <span className="text-red-500">*</span>
                                            </label>
                                            <input
                                                className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                                type="text"
                                                name="nombree_en"
                                                id="nombree_en"
                                                value={updateForm?.data?.name_en}
                                                onChange={(e) => updateForm.setData('name_en', e.target.value)}
                                            />
                                        </div>
                                    </div>
                                    <div className="flex flex-row gap-2">
                                        <div className="flex flex-col gap-3">
                                            <label htmlFor="texto">Texto {'(Espaè´–ol)'}</label>
                                            <CustomReactQuill additionalStyles="max-w-[380px]" value={text_es} onChange={setTextEs} />
                                        </div>
                                        <div className="flex flex-col gap-3">
                                            <label htmlFor="texto">Texto {'(Ingles)'}</label>
                                            <CustomReactQuill additionalStyles="max-w-[380px]" value={text_en} onChange={setTextEn} />
                                        </div>
                                    </div>

                                    <label htmlFor="categoria">
                                        Ambientes <span className="text-red-500">*</span>
                                    </label>
                                    <Select
                                        options={ambientes?.map((ambiente) => ({
                                            value: ambiente.id,
                                            label: ambiente.name_es,
                                        }))}
                                        value={ambienteSelected}
                                        onChange={(options) => setAmbienteSelected(options || [])}
                                        className=""
                                        name="categoria"
                                        id="categoria"
                                        isMulti
                                    />

                                    <label htmlFor="imagennn">Imagen (600px Ancho) (651px Alto)</label>
                                    <input
                                        className="file:bg-primary-orange focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 file:cursor-pointer file:rounded-sm file:px-2 file:py-1 file:text-white focus:outline"
                                        type="file"
                                        name="imagennn"
                                        id="imagennn"
                                        onChange={(e) => updateForm.setData('image', e.target.files[0])}
                                    />

                                    <div className="flex justify-end gap-4">
                                        <button
                                            type="button"
                                            onClick={() => setEdit(false)}
                                            className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                        >
                                            Cancelar
                                        </button>
                                        <button
                                            type="submit"
                                            className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                        >
                                            Actualizar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </motion.div>
                )}
            </AnimatePresence>
        </tr>
    );
}

import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import CustomReactQuill from './CustomReactQuill';

export default function ComercioTarjetaRow({ tarjeta }) {
    const [edit, setEdit] = useState(false);

    const updateForm = useForm({
        order: tarjeta?.order,
        name_es: tarjeta?.name_es,
        name_en: tarjeta?.name_en,
        text_es: tarjeta?.text_es,
        text_en: tarjeta?.text_en,
        id: tarjeta?.id,
    });

    const [textEs, setTextEs] = useState(tarjeta?.text_es);
    const [textEn, setTextEn] = useState(tarjeta?.text_en);

    useEffect(() => {
        updateForm.setData('text_es', textEs);
    }, [textEs]);

    useEffect(() => {
        updateForm.setData('text_en', textEn);
    }, [textEn]);

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        updateForm.post(route('admin.comercio-tarjetas.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Tarjeta actualizada correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar tarjeta');
                console.log(errors);
            },
        });
    };

    const deleteTarjeta = () => {
        if (confirm('¿Estas seguro de eliminar esta tarjeta?')) {
            updateForm.delete(route('admin.comercio-tarjetas.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Tarjeta eliminada correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar tarjeta');
                    console.log(errors);
                },
            });
        }
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="align-middle">{tarjeta?.order}</td>
            <td className="align-middle">{tarjeta?.name_es}</td>
            <td className="align-middle">{tarjeta?.name_en}</td>
            <td className="h-[90px] align-middle">
                <div className="line-clamp-3 max-w-[200px] overflow-hidden break-words" dangerouslySetInnerHTML={{ __html: tarjeta?.text_es }} />
            </td>
            <td className="h-[90px] align-middle">
                <div className="line-clamp-3 max-w-[200px] overflow-hidden break-words" dangerouslySetInnerHTML={{ __html: tarjeta?.text_en }} />
            </td>
            <td className="h-[90px] align-middle">
                <img src={tarjeta?.image} className="h-full w-full object-contain" alt="" />
            </td>

            <td className="w-[140px] text-center">
                <div className="flex flex-row justify-center gap-3">
                    <button onClick={() => setEdit(true)} className="h-10 w-10 rounded-md border border-blue-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faPen} size="lg" color="#3b82f6" />
                    </button>
                    <button onClick={deleteTarjeta} className="h-10 w-10 rounded-md border border-red-500 px-2 py-1 text-white">
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
                                <h2 className="mb-4 text-2xl font-semibold">Actualizar tarjeta</h2>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="col-span-2 flex flex-col gap-2">
                                        <label htmlFor="ordennn">Orden</label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="ordennn"
                                            id="ordennn"
                                            defaultValue={tarjeta?.order}
                                            onChange={(e) => updateForm.setData('order', e.target.value)}
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="nombree">
                                            Nombre {'(Español)'} <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="nombree"
                                            id="nombree"
                                            defaultValue={tarjeta?.name_es}
                                            onChange={(e) => updateForm.setData('name_es', e.target.value)}
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="nombree_en">
                                            Nombre {'(Inglés)'} <span className="text-red-500">*</span>
                                        </label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="nombree_en"
                                            id="nombree_en"
                                            defaultValue={tarjeta?.name_en}
                                            onChange={(e) => updateForm.setData('name_en', e.target.value)}
                                        />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="subtitulo">
                                            Texto {'(Español)'} <span className="text-red-500">*</span>
                                        </label>
                                        <CustomReactQuill additionalStyles="max-w-[380px]" value={textEs} onChange={setTextEs} />
                                    </div>
                                    <div className="flex flex-col gap-2">
                                        <label htmlFor="subtitulo_en">
                                            Texto {'(Inglés)'} <span className="text-red-500">*</span>
                                        </label>
                                        <CustomReactQuill additionalStyles="max-w-[380px]" value={textEn} onChange={setTextEn} />
                                    </div>
                                    <div className="col-span-2 flex flex-col gap-2">
                                        <label htmlFor="imagennn">Imagen (60px Ancho) (60px Alto)</label>
                                        <input
                                            className="file:bg-primary-orange focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 file:cursor-pointer file:rounded-sm file:px-2 file:py-1 file:text-white focus:outline"
                                            type="file"
                                            name="imagennn"
                                            id="imagennn"
                                            onChange={(e) => updateForm.setData('image', e.target.files[0])}
                                        />
                                    </div>

                                    <div className="col-span-2 flex justify-end gap-4">
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

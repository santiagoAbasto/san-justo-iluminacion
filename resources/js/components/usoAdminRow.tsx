import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';

export default function UsoAdminRow({ uso }) {
    const [edit, setEdit] = useState(false);

    const { espacios } = usePage().props;

    const updateForm = useForm({
        name_es: uso?.name_es,
        name_en: uso?.name_en,
        espacio_id: uso?.espacio_id,
        order: uso?.order,
        id: uso?.id,
    });

    const handleUpdate = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        updateForm.post(route('admin.usos.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Uso actualizado correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar uso');
                console.log(errors);
            },
        });
    };

    const deleteUso = () => {
        if (confirm('¿Estas seguro de eliminar este uso?')) {
            updateForm.delete(route('admin.usos.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Uso eliminada correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar uso');
                    console.log(errors);
                },
            });
        }
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="align-middle">{uso?.order}</td>
            <td className="h-[90px] align-middle">{uso?.name_es}</td>
            <td className="h-[90px] align-middle">{uso?.name_en}</td>
            <td className="h-[90px] align-middle">{uso?.espacio?.name_es}</td>

            <td className="w-[140px] text-center">
                <div className="flex flex-row justify-center gap-3">
                    <button onClick={() => setEdit(true)} className="h-10 w-10 rounded-md border border-blue-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faPen} size="lg" color="#3b82f6" />
                    </button>
                    <button onClick={deleteUso} className="h-10 w-10 rounded-md border border-red-500 px-2 py-1 text-white">
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
                        <form onSubmit={handleUpdate} method="POST" className="text-black">
                            <div className="w-[500px] rounded-md bg-white p-4">
                                <h2 className="mb-4 text-2xl font-semibold">Actualizar Uso</h2>
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
                                                Nombre {'(Español)'} <span className="text-red-500">*</span>
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

                                    <div className="flex w-full flex-col gap-3">
                                        <label htmlFor="espacio_id">
                                            Espacios <span className="text-red-500">*</span>
                                        </label>
                                        <select
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            name="espacio_id"
                                            id="espacio_id"
                                            value={updateForm?.data?.espacio_id || ''}
                                            onChange={(e) => updateForm.setData('espacio_id', e.target.value)}
                                        >
                                            <option value="">Seleccione un espacio</option>
                                            {espacios.map((espacio) => (
                                                <option className="text-black" key={espacio.id} value={espacio.id}>
                                                    {espacio.name_es}
                                                </option>
                                            ))}
                                        </select>
                                    </div>  

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

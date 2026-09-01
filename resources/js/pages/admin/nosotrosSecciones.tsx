import CustomReactQuill from '@/components/CustomReactQuill';
import NosotrosSeccionesRow from '@/components/nosotrosSeccionesRow';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';

export default function NosotrosSecciones() {
    const { secciones } = usePage().props;

    const { data, setData, post, reset } = useForm({
        name_es: '',
    });

    const [searchTerm, setSearchTerm] = useState('');
    const [createView, setCreateView] = useState(false);

    const [textEs, setTextEs] = useState('');
    const [textEn, setTextEn] = useState('');

    useEffect(() => {
        setData('text_es', textEs);
    }, [textEs]);

    useEffect(() => {
        setData('text_en', textEn);
    }, [textEn]);

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route('admin.nosotros-secciones.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Seccion creada correctamente');
                reset();
                setCreateView(false);
            },
            onError: (errors) => {
                toast.error('Error al crear seccion');
                console.log(errors);
            },
        });
    };

    return (
        <Dashboard>
            <div className="flex w-full flex-col p-6">
                <AnimatePresence>
                    {createView && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            exit={{ opacity: 0 }}
                            className="fixed top-0 left-0 z-50 flex h-full w-full items-center justify-center bg-black/50 text-left"
                        >
                            <form onSubmit={handleSubmit} method="POST" className="max-h-[90vh] overflow-y-auto text-black">
                                <div className="w-[800px] rounded-md bg-white p-4">
                                    <h2 className="mb-4 text-2xl font-semibold">Cargar sección</h2>
                                    <div className="grid grid-cols-2 gap-4">
                                        <div className="col-span-2 flex flex-col gap-2">
                                            <label htmlFor="ordennn">Orden</label>
                                            <input
                                                className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                                type="text"
                                                name="ordennn"
                                                id="ordennn"
                                                onChange={(e) => setData('order', e.target.value)}
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
                                                onChange={(e) => setData('name_es', e.target.value)}
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
                                                onChange={(e) => setData('name_en', e.target.value)}
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
                                            <label htmlFor="imagennn">Imagen (600px Ancho) (440px Alto)</label>
                                            <input
                                                className="file:bg-primary-orange focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 file:cursor-pointer file:rounded-sm file:px-2 file:py-1 file:text-white focus:outline"
                                                type="file"
                                                name="imagennn"
                                                id="imagennn"
                                                onChange={(e) => setData('image', e.target.files[0])}
                                            />
                                        </div>

                                        <div className="col-span-2 flex justify-end gap-4">
                                            <button
                                                type="button"
                                                onClick={() => setCreateView(false)}
                                                className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                            >
                                                Cancelar
                                            </button>
                                            <button
                                                type="submit"
                                                className="border-primary-orange text-primary-orange hover:bg-primary-orange rounded-md border px-2 py-1 transition duration-300 hover:text-white"
                                            >
                                                Guardar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </motion.div>
                    )}
                </AnimatePresence>
                <div className="mx-auto flex w-full flex-col gap-3">
                    <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Secciones</h2>
                    <div className="flex h-fit w-full flex-row gap-5">
                        <input
                            type="text"
                            placeholder="Buscar seccion..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3"
                        />
                        <button
                            onClick={() => setCreateView(true)}
                            className="bg-primary-orange w-[200px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Cargar seccion
                        </button>
                    </div>

                    <div className="flex w-full justify-center">
                        <table className="w-full border text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead className="bg-gray-300 text-sm font-medium text-black uppercase">
                                <tr>
                                    <td className="text-center">ORDEN</td>

                                    <td className="text-center">NOMBRE {'(Español)'}</td>
                                    <td className="text-center">NOMBRE {'(Inglés)'}</td>
                                    <td className="text-center">TEXTO {'(Español)'}</td>
                                    <td className="text-center">TEXTO {'(Inglés)'}</td>
                                    <td className="py-2 text-center">IMAGEN</td>
                                    <td className="text-center">EDITAR</td>
                                </tr>
                            </thead>
                            <tbody className="text-center">
                                {secciones?.map((seccion) => <NosotrosSeccionesRow key={seccion.id} seccion={seccion} />)}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </Dashboard>
    );
}

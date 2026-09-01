import CustomReactQuill from '@/components/CustomReactQuill';
import LineasAdminRow from '@/components/lineasAdminRow';
import { router, useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Select from 'react-select';
import Dashboard from './dashboard';

export default function LineasAdmin() {
    const { lineas, ambientes } = usePage().props;
    const uniqueAmbientes = Array.from(
        new Map((ambientes || []).map((ambiente) => [ambiente?.id, ambiente])).values(),
    );

    const { data, setData, post, reset } = useForm({
        name_es: '',
        ambientes: [],
    });

    const [searchTerm, setSearchTerm] = useState('');
    const [createView, setCreateView] = useState(false);
    const [text_es, setTextEs] = useState('');
    const [text_en, setTextEn] = useState('');
    const [ambienteSelected, setAmbienteSelected] = useState([]);

    useEffect(() => {
        setData(
            'ambientes',
            ambienteSelected.map((a) => a.value),
        );
    }, [ambienteSelected]);

    useEffect(() => {
        setData('text_es', text_es);
    }, [text_es]);

    useEffect(() => {
        setData('text_en', text_en);
    }, [text_en]);

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route('admin.lineas.store'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Linea creada correctamente');
                reset();
                setCreateView(false);
            },
            onError: (errors) => {
                toast.error('Error al crear linea');
                console.log(errors);
            },
        });
    };

    // Manejadores para la paginación del backend
    const handlePageChange = (page) => {
        router.get(
            route('admin.lineas'),
            {
                page: page,
                search: searchTerm,
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
    };

    // Función para realizar la búsqueda
    const handleSearch = () => {
        router.get(
            route('admin.lineas'),
            {
                search: searchTerm,
                page: 1, // Resetear a la primera página al buscar
            },
            {
                preserveState: true,
                preserveScroll: true,
            },
        );
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
                                    <h2 className="mb-4 text-2xl font-semibold">Crear Linea</h2>
                                    <div className="flex flex-col gap-4">
                                        <label htmlFor="ordennn">Orden</label>
                                        <input
                                            className="focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 focus:outline"
                                            type="text"
                                            name="ordennn"
                                            id="ordennn"
                                            onChange={(e) => setData('order', e.target.value)}
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
                                                    onChange={(e) => setData('name_es', e.target.value)}
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
                                                    onChange={(e) => setData('name_en', e.target.value)}
                                                />
                                            </div>
                                        </div>
                                        <div className="flex flex-row gap-2">
                                            <div className="flex flex-col gap-3">
                                                <label htmlFor="texto">Texto {'(Español)'}</label>
                                                <CustomReactQuill additionalStyles="max-w-[380px]" value={text_es} onChange={setTextEs} />
                                            </div>
                                            <div className="flex flex-col gap-3">
                                                <label htmlFor="texto">Texto {'(Ingles)'}</label>
                                                <CustomReactQuill additionalStyles="max-w-[380px]" value={text_en} onChange={setTextEn} />
                                            </div>
                                        </div>

                                        <label htmlFor="subcategoria">
                                            Ambientes <span className="text-red-500">*</span>
                                        </label>
                                        <Select
                                            options={uniqueAmbientes.map((ambiente) => ({
                                                value: ambiente.id,
                                                label: ambiente.name_es,
                                            }))}
                                            onChange={(options) => setAmbienteSelected(options)}
                                            className=""
                                            name="subcategoria"
                                            id="subcategoria"
                                            isMulti
                                        />

                                        <label htmlFor="imagennn">Imagen (600px Ancho) (651px Alto)</label>
                                        <input
                                            className="file:bg-primary-orange focus:outline-primary-orange rounded-md p-2 outline outline-gray-300 file:cursor-pointer file:rounded-sm file:px-2 file:py-1 file:text-white focus:outline"
                                            type="file"
                                            name="imagennn"
                                            id="imagennn"
                                            onChange={(e) => setData('image', e.target.files[0])}
                                        />

                                        <div className="flex justify-end gap-4">
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
                    <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Lineas</h2>
                    <div className="flex h-fit w-full flex-row gap-5">
                        <input
                            type="text"
                            placeholder="Buscar linea..."
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                            className="w-full rounded-md border border-gray-300 px-3"
                        />
                        <button
                            onClick={handleSearch}
                            className="bg-primary-orange w-[200px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Buscar
                        </button>
                        <button
                            onClick={() => setCreateView(true)}
                            className="bg-primary-orange w-[200px] rounded px-4 py-1 font-bold text-white hover:bg-orange-400"
                        >
                            Crear linea
                        </button>
                    </div>

                    <div className="flex w-full justify-center">
                        <table className="w-full border text-left text-sm text-gray-500 rtl:text-right dark:text-gray-400">
                            <thead className="bg-gray-300 text-sm font-medium text-black uppercase">
                                <tr>
                                    <td className="text-center">ORDEN</td>
                                    <td className="py-2 text-center">NOMBRE</td>
                                    <td className="text-center">TEXTO</td>
                                    <td className="text-center">AMBIENTES</td>
                                    <td className="text-center">IMAGEN</td>
                                    <td className="text-center">DESTACADO</td>
                                    <td className="text-center">EDITAR</td>
                                </tr>
                            </thead>
                            <tbody className="text-center">{lineas.data?.map((linea) => <LineasAdminRow key={linea.id} linea={linea} />)}</tbody>
                        </table>
                    </div>

                    {/* Paginación con datos del backend */}
                    <div className="mt-4 flex justify-center">
                        {lineas.links && (
                            <div className="flex items-center">
                                {lineas.links.map((link, index) => (
                                    <button
                                        key={index}
                                        onClick={() => link.url && handlePageChange(link.url.split('page=')[1])}
                                        disabled={!link.url}
                                        className={`px-4 py-2 ${
                                            link.active
                                                ? 'bg-primary-orange text-white'
                                                : link.url
                                                  ? 'bg-gray-300 text-black'
                                                  : 'bg-gray-200 text-gray-500 opacity-50'
                                        } ${index === 0 ? 'rounded-l-md' : ''} ${index === lineas.links.length - 1 ? 'rounded-r-md' : ''}`}
                                        dangerouslySetInnerHTML={{ __html: link.label }}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Información de paginación */}
                    <div className="mt-2 text-center text-sm text-gray-600">
                        Mostrando {lineas.from || 0} a {lineas.to || 0} de {lineas.total} resultados
                    </div>
                </div>
            </div>
        </Dashboard>
    );
}

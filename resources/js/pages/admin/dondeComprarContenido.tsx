import CustomReactQuill from '@/components/CustomReactQuill';
import { useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';
export default function DondeComprarContenido() {
    const { dondeComprarContenido } = usePage().props;

    const { data, setData, post } = useForm({
        title_es: dondeComprarContenido?.title_es,
        title_en: dondeComprarContenido?.title_en,
        text_es: dondeComprarContenido?.text_es,
        text_en: dondeComprarContenido?.text_en,
    });

    const [textEs, setTextEs] = useState(dondeComprarContenido?.text_es);
    const [textEn, setTextEn] = useState(dondeComprarContenido?.text_en);

    useEffect(() => {
        setData('text_es', textEs);
    }, [textEs]);

    useEffect(() => {
        setData('text_en', textEn);
    }, [textEn]);

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('admin.donde-comprar-contenido.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Contenido actualizado correctamente');
            },
            onError: (errors) => {
                toast.error('Error al actualizar el contenido');
                console.log(errors);
            },
        });
    };

    return (
        <Dashboard>
            <form onSubmit={handleSubmit} className="flex flex-col gap-4 p-6" action="">
                <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Contenido principal</h2>
                <div className="grid grid-cols-2 gap-x-6 gap-y-8">
                    <div className="w-full">
                        <label htmlFor="title_es" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                            <p>Titulo {'(Español)'}</p>
                        </label>
                        <div className="mt-2">
                            <div className="focus-within:outline-primary-orange flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2">
                                <div className="shrink-0 text-base text-gray-500 select-none sm:text-sm/6"></div>
                                <input
                                    value={data.title_es}
                                    onChange={(ev) => {
                                        setData('title_es', ev.target.value);
                                    }}
                                    id="title_es"
                                    name="title_es"
                                    type="text"
                                    className="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="w-full">
                        <label htmlFor="title_en" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                            <p>Titulo {'(Ingles)'}</p>
                        </label>
                        <div className="mt-2">
                            <div className="focus-within:outline-primary-orange flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2">
                                <div className="shrink-0 text-base text-gray-500 select-none sm:text-sm/6"></div>
                                <input
                                    value={data.title_en}
                                    onChange={(ev) => {
                                        setData('title_en', ev.target.value);
                                    }}
                                    id="title_en"
                                    name="title_en"
                                    type="text"
                                    className="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="w-full">
                        <label htmlFor="text_es" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                            <p>Texto {'(Espanol)'}</p>
                        </label>
                        <div className="mt-2">
                            <CustomReactQuill value={textEs} onChange={setTextEs} />
                        </div>
                    </div>

                    <div className="w-full">
                        <label htmlFor="text_en" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                            <p>Texto {'(Ingles)'}</p>
                        </label>
                        <div className="mt-2">
                            <CustomReactQuill value={textEn} onChange={setTextEn} />
                        </div>
                    </div>
                </div>

                <div className="mt-5">
                    <button className="text-primary-orange border-primary-orange hover:bg-primary-orange rounded-full border px-4 py-2 font-bold transition duration-300 hover:text-white">
                        Actualizar
                    </button>
                </div>
            </form>
        </Dashboard>
    );
}

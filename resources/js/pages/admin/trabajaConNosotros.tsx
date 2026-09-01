import CustomReactQuill from '@/components/CustomReactQuill';
import { useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';
export default function TrabajaConNosotros() {
    const { trabaja } = usePage().props;

    const { data, setData, post } = useForm({
        title_es: trabaja?.title_es || '',
        title_en: trabaja?.title_en || '',
        email: trabaja?.email || '',
    });

    const [textEs, setTextEs] = useState(trabaja?.text_es);
    const [textEn, setTextEn] = useState(trabaja?.text_en);

    useEffect(() => {
        setData('text_es', textEs);
    }, [textEs]);

    useEffect(() => {
        setData('text_en', textEn);
    }, [textEn]);

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route('admin.trabaja-con-nosotros.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Campos actualizados correctamente');
            },
            onError: (errors) => {
                toast.error('Error al actualizar los campos');
                console.log(errors);
            },
        });
    };

    return (
        <Dashboard>
            <form onSubmit={handleSubmit} className="flex flex-col gap-4 p-6" action="">
                <h2 className="border-primary-orange text-primary-orange text-bold w-full border-b-2 text-2xl">Trabaja con nosotros</h2>
                <div className="grid grid-cols-2 gap-x-6 gap-y-8 max-sm:grid-cols-1">
                    <div className="flex w-full flex-col gap-2">
                        <label htmlFor="username" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                            <p>Título {'(Español)'} </p>
                        </label>
                        <div className="mt-2">
                            <div className="focus-within:outline-primary-orange flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2">
                                <div className="shrink-0 text-base text-gray-500 select-none sm:text-sm/6"></div>
                                <input
                                    value={data.title_es}
                                    onChange={(ev) => {
                                        setData('title_es', ev.target.value);
                                    }}
                                    id="username"
                                    name="username"
                                    type="text"
                                    className="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                                />
                            </div>
                        </div>
                    </div>

                    <div className="flex w-full flex-row gap-2">
                        <div className="flex w-full flex-col gap-2">
                            <label htmlFor="pedidos" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                                <p>Titulo {'(Inglés)'} </p>
                            </label>
                            <div className="mt-2">
                                <div className="focus-within:outline-primary-orange flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2">
                                    <div className="shrink-0 text-base text-gray-500 select-none sm:text-sm/6"></div>
                                    <input
                                        value={data.title_en}
                                        onChange={(ev) => {
                                            setData('title_en', ev.target.value);
                                        }}
                                        id="pedidos"
                                        name="pedidos"
                                        type="text"
                                        className="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="flex flex-row gap-4">
                        <div className="flex w-full flex-col gap-3">
                            <label htmlFor="telefono" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                                <p>Texto {'(Español)'}</p>
                            </label>
                            <CustomReactQuill value={textEs} onChange={setTextEs} />
                        </div>
                    </div>
                    <div className="flex flex-row gap-4">
                        <div className="flex w-full flex-col gap-3">
                            <label htmlFor="location" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                                <p>Texto {'(Inglés)'}</p>
                            </label>
                            <CustomReactQuill value={textEn} onChange={setTextEn} />
                        </div>
                    </div>

                    <div className="col-span-2 flex w-full flex-row gap-2">
                        <div className="flex w-full flex-col gap-2">
                            <label htmlFor="email" className="flex flex-row items-center gap-2 text-sm/6 font-medium text-gray-900">
                                <p>Email </p>
                            </label>
                            <div className="mt-2">
                                <div className="focus-within:outline-primary-orange flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2">
                                    <div className="shrink-0 text-base text-gray-500 select-none sm:text-sm/6"></div>
                                    <input
                                        value={data.email}
                                        onChange={(ev) => {
                                            setData('email', ev.target.value);
                                        }}
                                        id="email"
                                        name="email"
                                        type="text"
                                        className="block min-w-0 grow py-1.5 pr-3 pl-1 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6"
                                    />
                                </div>
                            </div>
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

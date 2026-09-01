import { Head, useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import DefaultLayout from '../defaultLayout';

export default function Margenes() {
    const { tipo_de_productos, margenes } = usePage().props;

    const [margenGeneral, setMargenGeneral] = useState(margenes.general || 0);
    const [margenesTipos, setMargenesTipos] = useState(margenes?.tipos || {});

    const { data, setData, post, processing } = useForm({
        margenes: {
            general: 0,
            tipos: {},
        },
    });

    // Inicializar márgenes por tipos
    useEffect(() => {
        const margenesIniciales = {};
        tipo_de_productos.forEach((tipo) => {
            margenesIniciales[tipo.name] = margenes.tipos[tipo.name] || 0;
        });
        setMargenesTipos(margenesIniciales);
    }, [tipo_de_productos, margenes]);

    useEffect(() => {
        setData({
            margenes: {
                general: margenGeneral,
                tipos: margenesTipos,
            },
        });
    }, [margenGeneral, margenesTipos]);

    // Solo cambiar el estado local, sin hacer peticiones
    const changeMargenGeneral = (e) => {
        const valor = parseFloat(e.target.value) || 0;
        setMargenGeneral(valor);
    };

    // Solo cambiar el estado local, sin hacer peticiones
    const changeMargenTipo = (tipoNombre, e) => {
        const valor = parseFloat(e.target.value) || 0;
        setMargenesTipos((prev) => ({
            ...prev,
            [tipoNombre]: valor,
        }));
    };

    // Guardar todos los márgenes cuando se presiona el botón
    const guardarMargenes = () => {
        post('/margenes/guardar', {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                // Opcional: mostrar mensaje de éxito
                toast.success('Márgenes guardados correctamente');
                console.log('Márgenes guardados correctamente');
            },
            onError: (errors) => {
                toast.error('Error al guardar los márgenes');
                console.log('Errores:', errors);
            },
        });
    };

    return (
        <DefaultLayout>
            <Head>
                <title>Márgenes</title>
            </Head>
            <div className="mx-auto my-20 min-h-[50vh] w-[1200px] max-sm:w-full max-sm:px-4">
                {/* Mensaje de éxito */}

                <div className="flex flex-row gap-20 max-sm:flex-col">
                    {/* Margen General */}
                    <div className="flex flex-col gap-4">
                        <label className="font-bold" htmlFor="margen-general">
                            Catálogo en general
                        </label>
                        <div className="flex h-[48px] max-w-[392px] items-center justify-between rounded-sm border">
                            <input
                                defaultValue={margenGeneral}
                                onChange={changeMargenGeneral}
                                type="number"
                                id="margen-general"
                                min="0"
                                max="100"
                                step="1"
                                className="h-full w-[80%] pl-2 outline-none"
                            />
                            <span className="flex h-full w-[20%] items-center justify-center text-gray-300">%</span>
                        </div>
                        <button
                            onClick={guardarMargenes}
                            disabled={processing}
                            className="bg-primary-orange hover:text-primary-orange hover:outline-primary-orange w-[165px] rounded-sm px-4 py-2 text-white transition duration-300 hover:bg-transparent hover:outline disabled:opacity-50"
                        >
                            {processing ? 'Guardando...' : 'Guardar porcentajes'}
                        </button>
                    </div>

                    {/* Márgenes por Tipo */}
                    <div className="flex flex-col gap-5">
                        {tipo_de_productos.map((tipo) => (
                            <div key={tipo?.id} className="flex flex-col gap-4">
                                <label htmlFor={`margen-${tipo?.id}`} className="font-bold">
                                    {tipo?.name}
                                </label>
                                <div className="flex h-[48px] max-w-[392px] items-center justify-between rounded-sm border">
                                    <input
                                        defaultValue={margenesTipos[tipo?.name] || 0}
                                        onChange={(e) => changeMargenTipo(tipo?.name, e)}
                                        type="number"
                                        id={`margen-${tipo?.id}`}
                                        min="0"
                                        max="100"
                                        step="1"
                                        className="h-full w-[80%] pl-2 outline-none"
                                    />
                                    <span className="flex h-full w-[20%] items-center justify-center text-gray-300">%</span>
                                </div>
                            </div>
                        ))}
                        <button
                            onClick={guardarMargenes}
                            disabled={processing}
                            className="bg-primary-orange hover:text-primary-orange hover:outline-primary-orange w-[165px] rounded-sm px-4 py-2 text-white transition duration-300 hover:bg-transparent hover:outline disabled:opacity-50"
                        >
                            {processing ? 'Guardando...' : 'Guardar porcentajes'}
                        </button>
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}

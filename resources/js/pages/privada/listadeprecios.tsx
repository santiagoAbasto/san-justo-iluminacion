import ListaDePreciosRow from '@/components/listadepreciosRow';
import { Head, usePage } from '@inertiajs/react';
import DefaultLayout from '../defaultLayout';

export default function ListaDePrecios() {
    const { listaDePrecios } = usePage().props;

    console.log('Lista de precios:', listaDePrecios);

    return (
        <DefaultLayout>
            <Head>
                <title>Lista de precios</title>
            </Head>
            <div className="mx-auto min-h-[50vh] w-[1200px] py-20 max-sm:w-full">
                <div className="col-span-2 grid w-full items-start">
                    <div className="w-full">
                        <div className="bg-primary-orange grid h-[52px] grid-cols-5 items-center rounded-t-sm text-white max-sm:hidden">
                            <p></p>
                            <p>Nombre</p>
                            <p>Formato</p>
                            <p>Peso</p>
                            <p></p>
                        </div>
                        {listaDePrecios?.map((lista) => <ListaDePreciosRow key={lista?.id} lista={lista} />)}
                    </div>
                </div>
            </div>
        </DefaultLayout>
    );
}

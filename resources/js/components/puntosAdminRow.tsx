import { faPen, faTrash } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { useState } from 'react';
import toast from 'react-hot-toast';

export default function PuntosAdminRow({ puntoVenta }) {
    const [edit, setEdit] = useState(false);

    const { provincias } = usePage().props;

    const { data, setData, post } = useForm({
        nombre: puntoVenta?.nombre,
        direccion: puntoVenta?.direccion,
        provincia: puntoVenta?.provincia,
        localidad: puntoVenta?.localidad,
        telefono: puntoVenta?.telefono,
        email: puntoVenta?.email,
        latitud: puntoVenta?.latitud,
        longitud: puntoVenta?.longitud,
        activo: puntoVenta?.activo,
        id: puntoVenta?.id,
    });

    const update = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();

        post(route('admin.donde-comprar.update'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Punto de venta actualizado correctamente');
                setEdit(false);
            },
            onError: (errors) => {
                toast.error('Error al actualizar punto de venta');
                console.log(errors);
            },
        });
    };

    const deletePuntoVenta = () => {
        if (confirm('¿Estas seguro de eliminar este punto de venta?')) {
            post(route('admin.donde-comprar.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success('Punto de venta eliminada correctamente');
                },
                onError: (errors) => {
                    toast.error('Error al eliminar punto de venta');
                    console.log(errors);
                },
            });
        }
    };

    return (
        <tr className={`border text-black odd:bg-gray-100 even:bg-white`}>
            <td className="pl-5 text-left">{puntoVenta?.nombre}</td>
            <td className="text-left">{puntoVenta?.email}</td>
            <td className="h-[90px] text-left">{puntoVenta?.provincia}</td>
            <td className="text-left">{puntoVenta?.localidad}</td>

            <td className="w-[140px] text-center">
                <div className="flex flex-row justify-center gap-3">
                    <button onClick={() => setEdit(true)} className="h-10 w-10 rounded-md border border-blue-500 px-2 py-1 text-white">
                        <FontAwesomeIcon icon={faPen} size="lg" color="#3b82f6" />
                    </button>
                    <button onClick={deletePuntoVenta} className="h-10 w-10 rounded-md border border-red-500 px-2 py-1 text-white">
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
                        <form onSubmit={update} className="flex h-fit max-h-[90vh] w-[600px] flex-col gap-6 overflow-y-auto bg-white p-5 shadow-md">
                            <h2 className="text-xl font-bold text-black">Actualizar puntos de venta</h2>
                            <div className="grid w-full grid-cols-2 gap-3 text-[16px]">
                                <div className="flex flex-col gap-2">
                                    <label htmlFor="name" className="">
                                        Nombre de punto de venta
                                    </label>
                                    <input
                                        value={data?.nombre}
                                        onChange={(ev) => setData('nombre', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="name"
                                        id="name"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="email">Email</label>
                                    <input
                                        value={data?.email}
                                        onChange={(ev) => setData('email', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="email"
                                        name="email"
                                        id="email"
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="direccion">Dirección</label>
                                    <input
                                        value={data?.direccion}
                                        onChange={(ev) => setData('direccion', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="direccion"
                                        id="direccion"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="telefono">Telefono</label>
                                    <input
                                        value={data?.telefono}
                                        onChange={(ev) => setData('telefono', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="telefono"
                                        id="telefono"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="longitud">Longitud</label>
                                    <input
                                        value={data?.longitud}
                                        onChange={(ev) => setData('longitud', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="longitud"
                                        id="longitud"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="latitud">Latitud</label>
                                    <input
                                        value={data?.latitud}
                                        onChange={(ev) => setData('latitud', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        type="text"
                                        name="latitud"
                                        id="latitud"
                                        required
                                    />
                                </div>

                                <div className="flex flex-col gap-2">
                                    <label htmlFor="provincia">Provincia</label>
                                    <select
                                        required
                                        value={data?.provincia}
                                        onChange={(ev) => setData('provincia', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        name="provincia"
                                        id="provincia"
                                    >
                                        <option disabled selected value="">
                                            Selecciona una provincia
                                        </option>

                                        {provincias?.map((pr) => (
                                            <option key={pr.id} value={pr.name}>
                                                {pr.name}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                                <div className="flex flex-col gap-2">
                                    <label htmlFor="localidad">Localidad</label>
                                    <select
                                        required
                                        value={data?.localidad}
                                        onChange={(ev) => setData('localidad', ev.target.value)}
                                        className="focus:outline-primary-orange h-[45px] w-full pl-3 outline-1 outline-[#DDDDE0] transition duration-300"
                                        name="localidad"
                                        id="localidad"
                                    >
                                        <option disabled selected value="">
                                            Selecciona una localidad
                                        </option>

                                        {provincias
                                            ?.find((pr) => pr.name === data?.provincia)
                                            ?.localidades.map((loc, index) => (
                                                <option key={index} value={loc.name}>
                                                    {loc.name}
                                                </option>
                                            ))}
                                    </select>
                                </div>
                            </div>
                            <div className="flex flex-row justify-between gap-4">
                                <button
                                    type="button"
                                    onClick={() => setEdit(false)}
                                    className="bg-primary-orange col-span-2 h-[43px] w-full text-white"
                                >
                                    Cancelar
                                </button>
                                <button className="bg-primary-orange col-span-2 h-[43px] w-full text-white">Actualizar Punto de Venta</button>
                            </div>
                        </form>
                    </motion.div>
                )}
            </AnimatePresence>
        </tr>
    );
}

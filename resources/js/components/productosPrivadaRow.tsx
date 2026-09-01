import { faChevronDown, faChevronUp } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { router, useForm, usePage } from '@inertiajs/react';
import { Trash2 } from 'lucide-react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
/* import defaultPhoto from '../../images/defaultPhoto.png'; */

export default function ProductosPrivadaRow({ producto, margenSwitch, margen }) {
    const { auth, ziggy, margenes } = usePage().props;
    const { user } = auth;

    const [cantidad, setCantidad] = useState(producto?.qty != 1 ? producto?.qty : producto?.unidad_pack);

    useEffect(() => {
        if (producto?.rowId) {
            router.post(
                route('update'),
                {
                    qty: cantidad,
                    rowId: producto?.rowId,
                },
                {
                    preserveScroll: true,
                },
            );
        }
    }, [cantidad]);

    const { post, setData } = useForm({
        id: producto?.id,
        name: producto?.code,
        qty: cantidad,
        price: producto?.precio?.precio,
        rowId: producto?.rowId,
    });

    useEffect(() => {
        setData({
            id: producto?.id,
            name: producto?.code,
            qty: cantidad,
            price: producto?.precio?.precio,
            rowId: producto?.rowId,
        });
    }, [cantidad, producto]);

    const addToCart = (e) => {
        e.preventDefault();

        post(route('addtocart'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Producto agregado al carrito', { position: 'top-center' });
            },
            onError: (errors) => {
                toast.error('Error al agregar producto al carrito');
                console.log(errors);
            },
        });
    };

    const removeFromCart = (e) => {
        e.preventDefault();
        post(route('remove'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Producto eliminado del carrito', { position: 'top-center' });
            },
            onError: (errors) => {
                toast.error('Error al eliminar producto del carrito');
                console.log(errors);
            },
        });
    };

    return (
        <>
            {/* Vista desktop - tabla */}
            <div className="grid h-fit grid-cols-11 items-center border-b border-gray-200 py-2 text-[15px] text-black max-sm:hidden">
                <div className="h-[80px] w-[80px] rounded-sm border">
                    <img src={producto?.imagenes[0]?.image} className="h-full w-full object-contain" alt="" />
                </div>
                <p className="">{producto?.code_sr}</p>
                <p className="">{producto?.code}</p>
                <p className="">{producto?.marca?.name}</p>
                <p className="">{producto?.modelo?.name}</p>
                <p className="">{producto?.categoria?.name}</p>

                {/* mostrar uno debajo del otro */}

                {margenSwitch ? (
                    <div className="relative">
                        <p className="text-right">
                            ${' '}
                            {(
                                Number(Number(producto?.precio?.precio)) *
                                (1 + Number(margenes?.general ?? 0) / 100) *
                                (1 + Number(margenes?.tipos[producto?.categoria?.name] ?? 0) / 100) *
                                cantidad
                            )?.toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                        </p>
                        <p className="absolute w-full text-right text-gray-400 line-through">
                            ${' '}
                            {Number(Number(producto?.precio?.precio) * cantidad)?.toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                        </p>
                    </div>
                ) : (
                    <div className="relative">
                        <p className="text-right">
                            $ {Number(producto?.precio?.precio)?.toLocaleString('es-AR', { maximumFractionDigits: 2, minimumFractionDigits: 2 })}
                        </p>
                    </div>
                )}

                <p className="text-center text-green-500">
                    {[user?.descuento_uno, user?.descuento_dos, user?.descuento_tres]
                        .filter(Boolean)
                        .map((descuento) => descuento + '%')
                        .join(' + ')}
                </p>

                {margenSwitch ? (
                    <div className="relative">
                        <p className="text-right">
                            ${' '}
                            {(
                                Number(producto?.precio?.precio) *
                                cantidad *
                                (1 + Number(margenes?.general ?? 0) / 100) *
                                (1 + Number(margenes?.tipos[producto?.categoria?.name] ?? 0) / 100) *
                                (1 - Number(user?.descuento_uno) / 100) *
                                (1 - Number(user?.descuento_dos) / 100) *
                                (1 - Number(user?.descuento_tres) / 100)
                            ).toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                        </p>
                        <p className="absolute w-full text-right text-gray-400 line-through">
                            ${' '}
                            {(
                                Number(Number(producto?.precio?.precio)) *
                                (1 + Number(margenes?.general ?? 0) / 100) *
                                (1 + Number(margenes?.tipos[producto?.categoria?.name] ?? 0) / 100) *
                                (1 - Number(user?.descuento_uno) / 100) *
                                (1 - Number(user?.descuento_dos) / 100) *
                                (1 - Number(user?.descuento_tres) / 100) *
                                cantidad
                            )?.toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                        </p>
                    </div>
                ) : (
                    <p className="text-right">
                        ${' '}
                        {user?.descuento_uno &&
                            (
                                Number(producto?.precio?.precio) *
                                cantidad *
                                (1 - Number(user?.descuento_uno) / 100) *
                                (1 - Number(user?.descuento_dos) / 100) *
                                (1 - Number(user?.descuento_tres) / 100)
                            ).toLocaleString('es-AR', {
                                maximumFractionDigits: 2,
                                minimumFractionDigits: 2,
                            })}
                    </p>
                )}

                <p className="flex justify-end">
                    <div className="flex h-[38px] w-[99px] flex-row items-center border border-[#EEEEEE] px-2">
                        <input value={cantidad} type="text" className="h-full w-full focus:outline-none" />
                        <div className="flex h-full flex-col justify-center">
                            <button onClick={() => setCantidad(Number(cantidad) + Number(producto?.unidad_pack))} className="flex items-center">
                                <FontAwesomeIcon icon={faChevronUp} size="xs" />
                            </button>
                            <button
                                onClick={() =>
                                    setCantidad(
                                        Number(cantidad) > producto?.unidad_pack
                                            ? Number(cantidad) - Number(producto?.unidad_pack)
                                            : Number(cantidad),
                                    )
                                }
                                className="flex items-center"
                            >
                                <FontAwesomeIcon icon={faChevronDown} size="xs" />
                            </button>
                        </div>
                    </div>
                </p>

                <p className="flex justify-center">
                    {ziggy.location.includes('carrito') ? (
                        <button
                            type="button"
                            onClick={removeFromCart}
                            className="border-primary-orange flex h-[36px] w-[36px] items-center justify-center rounded-sm border transition duration-300 hover:scale-95 hover:shadow-sm"
                        >
                            <Trash2 size={16} color="#0992c9" />
                        </button>
                    ) : (
                        <button
                            onClick={addToCart}
                            className="border-primary-orange flex h-[36px] w-[36px] items-center justify-center rounded-sm border transition duration-300 hover:scale-95 hover:shadow-sm"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <g clip-path="url(#clip0_9500_465)">
                                    <path
                                        d="M1.36621 1.36667H2.69954L4.47288 9.64667C4.53793 9.94991 4.70666 10.221 4.95002 10.4132C5.19338 10.6055 5.49615 10.7069 5.80621 10.7H12.3262C12.6297 10.6995 12.9239 10.5955 13.1602 10.4052C13.3966 10.2149 13.561 9.94969 13.6262 9.65334L14.7262 4.7H3.41288M5.99954 14C5.99954 14.3682 5.70107 14.6667 5.33288 14.6667C4.96469 14.6667 4.66621 14.3682 4.66621 14C4.66621 13.6318 4.96469 13.3333 5.33288 13.3333C5.70107 13.3333 5.99954 13.6318 5.99954 14ZM13.3329 14C13.3329 14.3682 13.0344 14.6667 12.6662 14.6667C12.298 14.6667 11.9995 14.3682 11.9995 14C11.9995 13.6318 12.298 13.3333 12.6662 13.3333C13.0344 13.3333 13.3329 13.6318 13.3329 14Z"
                                        stroke="#0992C9"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </g>
                                <defs>
                                    <clipPath id="clip0_9500_465">
                                        <rect width="16" height="16" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>
                        </button>
                    )}
                </p>
            </div>

            {/* Vista móvil - tarjeta */}
            <div className="mb-3 rounded-lg border border-gray-200 bg-white p-4 shadow-sm sm:hidden">
                {/* Header de la tarjeta */}
                <div className="mb-3 flex items-start gap-3">
                    <div className="h-[60px] w-[60px] flex-shrink-0 rounded-sm border">
                        <img src={producto?.imagenes[0]?.image} className="h-full w-full object-contain" alt="" />
                    </div>
                    <div className="min-w-0 flex-1">
                        <div className="mb-1 flex items-center gap-2">
                            <p className="text-sm font-medium text-gray-900">Cod. Ori: {producto?.code}</p>
                            <p className="text-primary-orange text-sm font-medium">Cod. SR: {producto?.code_sr}</p>
                        </div>
                        <p className="line-clamp-2 text-sm text-gray-600">{producto?.name}</p>
                        {producto?.code_oem && (
                            <div className="grid grid-cols-2">
                                <p className="text-xs text-gray-500">Codigo OEM:</p>
                                <div className="flex flex-col gap-1">
                                    {producto?.code_oem?.split('/').map((item) => (
                                        <span key={item} className="block text-xs text-gray-500">
                                            {item}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>

                {/* Precio */}
                <div className="mb-3">
                    {margenSwitch ? (
                        <div className="relative">
                            <p className="text-right">
                                ${' '}
                                {(
                                    Number(Number(producto?.precio?.precio)) *
                                    (1 + Number(margenes?.general ?? 0) / 100) *
                                    (1 + Number(margenes?.tipos[producto?.categoria?.name] ?? 0) / 100) *
                                    cantidad
                                )?.toLocaleString('es-AR', {
                                    maximumFractionDigits: 2,
                                    minimumFractionDigits: 2,
                                })}
                            </p>
                            <p className="absolute top-0 -left-20 w-full text-right text-gray-400 line-through">
                                ${' '}
                                {Number(Number(producto?.precio?.precio) * cantidad)?.toLocaleString('es-AR', {
                                    maximumFractionDigits: 2,
                                    minimumFractionDigits: 2,
                                })}
                            </p>
                        </div>
                    ) : (
                        <div className="relative">
                            <p className="text-right">
                                ${' '}
                                {Number(producto?.precio?.precio * cantidad)?.toLocaleString('es-AR', {
                                    maximumFractionDigits: 2,
                                    minimumFractionDigits: 2,
                                })}
                            </p>
                        </div>
                    )}
                </div>

                {/* Controles de cantidad y acciones */}
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-3">
                        {/* Control de cantidad */}
                        <div className="flex h-[32px] w-[80px] flex-row items-center rounded border border-[#EEEEEE] px-2">
                            <input value={cantidad} type="text" className="h-full w-full text-center text-sm focus:outline-none" readOnly />
                            <div className="flex h-full flex-col justify-center">
                                <button onClick={() => setCantidad(Number(cantidad) + Number(producto?.unidad_pack))} className="flex items-center">
                                    <FontAwesomeIcon icon={faChevronUp} size="xs" />
                                </button>
                                <button
                                    onClick={() =>
                                        setCantidad(
                                            Number(cantidad) > producto?.unidad_pack
                                                ? Number(cantidad) - Number(producto?.unidad_pack)
                                                : Number(cantidad),
                                        )
                                    }
                                    className="flex items-center"
                                >
                                    <FontAwesomeIcon icon={faChevronDown} size="xs" />
                                </button>
                            </div>
                        </div>

                        {/* Indicador de stock */}
                    </div>

                    {/* Botón de acción */}
                    <div className="flex items-center gap-2">
                        {/* Subtotal */}
                        {margenSwitch ? (
                            <div className="relative">
                                <p className="text-right">
                                    ${' '}
                                    {(
                                        Number(producto?.precio?.precio) *
                                        cantidad *
                                        (1 + Number(margenes?.general ?? 0) / 100) *
                                        (1 + Number(margenes?.tipos[producto?.categoria?.name] ?? 0) / 100) *
                                        (1 - Number(user?.descuento_uno) / 100) *
                                        (1 - Number(user?.descuento_dos) / 100) *
                                        (1 - Number(user?.descuento_tres) / 100)
                                    ).toLocaleString('es-AR', {
                                        maximumFractionDigits: 2,
                                        minimumFractionDigits: 2,
                                    })}
                                </p>
                                <p className="absolute top-0 -left-20 w-full text-right text-gray-400 line-through">
                                    ${' '}
                                    {(
                                        Number(Number(producto?.precio?.precio)) *
                                        (1 + Number(margenes?.general ?? 0) / 100) *
                                        (1 + Number(margenes?.tipos[producto?.categoria?.name] ?? 0) / 100) *
                                        (1 - Number(user?.descuento_uno) / 100) *
                                        (1 - Number(user?.descuento_dos) / 100) *
                                        (1 - Number(user?.descuento_tres) / 100) *
                                        cantidad
                                    )?.toLocaleString('es-AR', {
                                        maximumFractionDigits: 2,
                                        minimumFractionDigits: 2,
                                    })}
                                </p>
                            </div>
                        ) : (
                            <p className="relative text-right">
                                <p
                                    className={`absolute -top-3 text-xs font-bold text-green-500 uppercase ${user?.descuento_uno == 0 ? 'hidden' : ''}`}
                                >
                                    Descuento
                                </p>
                                ${' '}
                                {user?.descuento_uno &&
                                    (
                                        Number(producto?.precio?.precio) *
                                        cantidad *
                                        (1 - Number(user?.descuento_uno) / 100) *
                                        (1 - Number(user?.descuento_dos) / 100) *
                                        (1 - Number(user?.descuento_tres) / 100)
                                    ).toLocaleString('es-AR', {
                                        maximumFractionDigits: 2,
                                        minimumFractionDigits: 2,
                                    })}
                            </p>
                        )}

                        {/* Botón */}
                        {ziggy.location.includes('carrito') ? (
                            <button type="button" onClick={removeFromCart} className="rounded p-2 text-red-500 hover:bg-red-50">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="20"
                                    height="20"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                >
                                    <path d="M3 6h18" />
                                    <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6" />
                                    <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2" />
                                </svg>
                            </button>
                        ) : (
                            <button onClick={addToCart} className="rounded p-2 text-blue-600 hover:bg-blue-50">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                    <g clip-path="url(#clip0_9500_971)">
                                        <path
                                            d="M1.36621 1.36667H2.69954L4.47288 9.64667C4.53793 9.94991 4.70666 10.221 4.95002 10.4132C5.19338 10.6055 5.49615 10.7069 5.80621 10.7H12.3262C12.6297 10.6995 12.9239 10.5955 13.1602 10.4052C13.3966 10.2149 13.561 9.94969 13.6262 9.65334L14.7262 4.7H3.41288M5.99954 14C5.99954 14.3682 5.70107 14.6667 5.33288 14.6667C4.96469 14.6667 4.66621 14.3682 4.66621 14C4.66621 13.6318 4.96469 13.3333 5.33288 13.3333C5.70107 13.3333 5.99954 13.6318 5.99954 14ZM13.3329 14C13.3329 14.3682 13.0344 14.6667 12.6662 14.6667C12.298 14.6667 11.9995 14.3682 11.9995 14C11.9995 13.6318 12.298 13.3333 12.6662 13.3333C13.0344 13.3333 13.3329 13.6318 13.3329 14Z"
                                            stroke="#0992C9"
                                            stroke-width="1.5"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_9500_971">
                                            <rect width="16" height="16" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </button>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}

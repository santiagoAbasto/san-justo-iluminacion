import { Link, usePage } from '@inertiajs/react';
import { useEffect, useRef, useState } from 'react';

const Navbar = () => {
    const { url } = usePage();
    const { logos, provincias, auth, carritoCount } = usePage().props;
    const user = auth.user;

    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [userMenu, setUserMenu] = useState(false);

    const userMenuRef = useRef(null);

    useEffect(() => {
        const handleClickOutside = (event) => {
            if (userMenu && userMenuRef.current && !userMenuRef.current.contains(event.target)) {
                setUserMenu(false);
            }
        };

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, [userMenu]);

    const privateLinks = [
        { title: 'Productos', href: '/privada/productos' },
        { title: 'Pedido', href: '/privada/carrito' },
        { title: 'Márgenes', href: '/privada/margenes' },
        { title: 'Mis pedidos', href: '/privada/pedidos' },
        { title: 'Lista de precios', href: '/privada/lista-de-precios' },
    ];

    return (
        <div className="sticky top-0 z-50 w-full bg-white shadow-sm transition-colors duration-300">
            {/* Overlay para el menú de usuario */}
            {userMenu && <div className="fixed top-0 left-0 z-40 h-screen w-screen bg-black/50" />}

            {/* Contenido principal navbar */}
            <div className="mx-auto flex h-[100px] w-full max-w-[1200px] items-center justify-between px-4 md:h-[100px] md:px-0">
                {/* Logo */}
                <Link href="/" className="flex-shrink-0">
                    <img
                        src={logos?.logo_principal || ''}
                        className="h-auto max-h-[50px] w-auto max-w-[80px] transition-all duration-300 md:max-h-[84px] md:max-w-[124px]"
                        alt="Logo"
                    />
                </Link>

                {/* Navegación desktop */}
                <div className="hidden items-center gap-6 md:flex lg:gap-8">
                    {privateLinks.map((link) => {
                        const isActive = url === link.href;
                        return (
                            <Link
                                key={link.href}
                                href={link.href}
                                className={`hover:text-primary-orange relative text-[14px] text-black transition-colors duration-300 lg:text-[16px] ${
                                    isActive ? 'text-primary-orange font-bold' : 'font-normal'
                                }`}
                            >
                                <p>{link.title}</p>
                                <span
                                    className={`absolute top-0 -right-2 flex size-3 ${carritoCount == 0 || link.href != '/privada/carrito' ? 'hidden' : ''}`}
                                >
                                    <span className="absolute inline-flex h-full w-full animate-ping rounded-full bg-sky-400 opacity-75"></span>
                                    <span className="relative inline-flex size-3 rounded-full bg-sky-500"></span>
                                </span>
                            </Link>
                        );
                    })}
                </div>

                {/* Sección derecha: botón de usuario y menú móvil */}
                <div className="flex items-center gap-4">
                    {/* Botón de usuario */}
                    <div className="relative">
                        <button
                            onClick={() => setUserMenu(!userMenu)}
                            className="bg-primary-orange hover:text-primary-orange hover:border-primary-orange h-[32px] w-[100px] rounded-sm text-xs text-white transition duration-300 hover:border hover:bg-transparent md:h-[36px] md:w-[117px] md:text-sm"
                        >
                            <span className="truncate px-2">{user?.name}</span>
                        </button>
                        {userMenu && (
                            <div
                                ref={userMenuRef}
                                className="absolute top-full right-0 z-50 mt-2 flex min-w-[200px] flex-col gap-4 rounded-sm bg-white p-4 shadow-lg"
                            >
                                <p className="text-sm font-bold md:text-lg">Bienvenido, {user?.name}!</p>
                                <Link
                                    method="post"
                                    href={route('logout')}
                                    className="bg-primary-orange flex items-center justify-center rounded-sm px-4 py-2 text-sm font-bold text-white md:text-base"
                                >
                                    Cerrar sesión
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Botón hamburguesa para móvil */}
                    <button
                        onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                        className="flex h-8 w-8 flex-col items-center justify-center space-y-1 md:hidden"
                        aria-label="Toggle menu"
                    >
                        <span
                            className={`block h-0.5 w-6 bg-gray-600 transition-all duration-300 ${mobileMenuOpen ? 'translate-y-1.5 rotate-45' : ''}`}
                        ></span>
                        <span className={`block h-0.5 w-6 bg-gray-600 transition-all duration-300 ${mobileMenuOpen ? 'opacity-0' : ''}`}></span>
                        <span
                            className={`block h-0.5 w-6 bg-gray-600 transition-all duration-300 ${mobileMenuOpen ? '-translate-y-1.5 -rotate-45' : ''}`}
                        ></span>
                    </button>
                </div>
            </div>

            {/* Menú móvil */}
            <div
                className={`overflow-hidden transition-all duration-300 ease-in-out md:hidden ${mobileMenuOpen ? 'max-h-96 border-t border-gray-200' : 'max-h-0'}`}
            >
                <div className="bg-white shadow-lg">
                    <div className="py-2">
                        {privateLinks.map((link) => {
                            const isActive = url === link.href;
                            return (
                                <Link
                                    key={link.href}
                                    href={link.href}
                                    className={`hover:text-primary-orange relative block px-4 py-3 text-sm text-gray-700 transition-colors duration-300 hover:bg-gray-50 ${
                                        isActive ? 'text-primary-orange bg-orange-50 font-bold' : ''
                                    }`}
                                    onClick={() => setMobileMenuOpen(false)}
                                >
                                    <span className="flex items-center justify-between">
                                        {link.title}
                                        {carritoCount > 0 && link.href === '/privada/carrito' && (
                                            <span className="flex size-2">
                                                <span className="absolute inline-flex h-2 w-2 animate-ping rounded-full bg-sky-400 opacity-75"></span>
                                                <span className="relative inline-flex size-2 rounded-full bg-sky-500"></span>
                                            </span>
                                        )}
                                    </span>
                                </Link>
                            );
                        })}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Navbar;

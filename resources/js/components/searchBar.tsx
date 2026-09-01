import { router, useForm, usePage } from '@inertiajs/react';
import { useEffect } from 'react';

const SearchBar = () => {
    const { categorias, marcas, modelos, filters } = usePage().props;

    const { data, setData, get } = useForm({
        tipo: filters?.tipo || '',
        marca: filters?.marca || '',
        modelo: filters?.modelo || '',
        code: filters?.code || '',
        code_sr: filters?.code_sr || '',
    });

    // Sincronizar el estado cuando cambien los filtros desde el backend
    useEffect(() => {
        setData({
            tipo: filters?.tipo || '',
            marca: filters?.marca || '',
            modelo: filters?.modelo || '',
            code: filters?.code || '',
            code_sr: filters?.code_sr || '',
        });
    }, [filters]);

    // Función para manejar el envío del formulario
    const handleSubmit = (e) => {
        e.preventDefault();
        get(route('index.privada.productos'), {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Función para eliminar un filtro específico
    const removeFilter = (filterName) => {
        // Actualizar el estado local primero
        setData(filterName, '');

        const newFilters = { ...data };
        delete newFilters[filterName];

        // Limpiar filtros vacíos
        Object.keys(newFilters).forEach((key) => {
            if (!newFilters[key]) {
                delete newFilters[key];
            }
        });

        router.get(route('index.privada.productos'), newFilters, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    // Componente para el botón de eliminar filtro
    const ClearFilterButton = ({ filterValue, filterName }) => {
        if (!filterValue) return null;

        return (
            <button
                type="button"
                onClick={() => removeFilter(filterName)}
                className="absolute top-1/2 right-5 -translate-y-1/2 transform text-gray-400 transition duration-200 hover:text-red-500"
                title="Eliminar filtro"
            >
                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        );
    };

    return (
        <div className="flex min-h-[195px] w-full items-center bg-[#F5F5F5] py-4 md:h-[195px] md:py-0">
            <form
                onSubmit={handleSubmit}
                className="mx-auto flex h-auto w-full max-w-[1200px] flex-col items-center gap-6 px-4 py-4 md:h-[123px] md:w-[1200px] md:flex-row md:px-0 md:py-0"
            >
                {/* Sección de tipo de producto */}
                <div className="flex w-full flex-col gap-4 md:w-[25%]">
                    <h2 className="text-primary-orange border-b pb-1 text-[20px] font-bold md:text-[24px]">Por tipo de producto</h2>
                    <div className="flex flex-col gap-2">
                        <label htmlFor="tipo" className="text-[14px] md:text-[16px]">
                            Tipo de producto
                        </label>
                        <div className="relative">
                            <select
                                className="focus:outline-primary-orange w-full rounded-sm bg-white p-2 pr-10 text-sm outline-transparent transition duration-300 focus:outline md:text-base"
                                name="tipo"
                                id="tipo"
                                value={data.tipo}
                                onChange={(e) => setData('tipo', e.target.value)}
                            >
                                <option value="">Elegir el tipo de producto</option>
                                {categorias?.map((categoria) => (
                                    <option key={categoria.id} value={categoria.id}>
                                        {categoria.name}
                                    </option>
                                ))}
                            </select>
                            <ClearFilterButton filterValue={data.tipo} filterName="tipo" />
                        </div>
                    </div>
                </div>

                {/* Sección de vehículo/código */}
                <div className="flex w-full flex-col gap-4 md:w-[75%]">
                    <h2 className="text-primary-orange border-b pb-1 text-[20px] font-bold md:text-[24px]">Por vehículo / Código</h2>
                    <div className="flex flex-col gap-4 md:flex-row md:gap-6">
                        {/* Marca */}
                        <div className="flex w-full flex-col gap-2">
                            <label htmlFor="marca" className="text-[14px] md:text-[16px]">
                                Marca
                            </label>
                            <div className="relative">
                                <select
                                    className="focus:outline-primary-orange w-full rounded-sm bg-white p-2 pr-10 text-sm outline-transparent transition duration-300 focus:outline md:text-base"
                                    name="marca"
                                    id="marca"
                                    value={data.marca}
                                    onChange={(e) => setData('marca', e.target.value)}
                                >
                                    <option value="">Elegir marca</option>
                                    {marcas?.map((marca) => (
                                        <option key={marca.id} value={marca.id}>
                                            {marca.name}
                                        </option>
                                    ))}
                                </select>
                                <ClearFilterButton filterValue={data.marca} filterName="marca" />
                            </div>
                        </div>

                        {/* Modelo */}
                        <div className="flex w-full flex-col gap-2">
                            <label htmlFor="modelo" className="text-[14px] md:text-[16px]">
                                Modelo
                            </label>
                            <div className="relative">
                                <select
                                    className="focus:outline-primary-orange w-full rounded-sm bg-white p-2 pr-10 text-sm outline-transparent transition duration-300 focus:outline md:text-base"
                                    name="modelo"
                                    id="modelo"
                                    value={data.modelo}
                                    onChange={(e) => setData('modelo', e.target.value)}
                                >
                                    <option value="">Elegir modelo</option>
                                    {modelos
                                        ?.filter((mod) => !data.marca || mod?.marca_id == data.marca)
                                        ?.map((modelo) => (
                                            <option key={modelo.id} value={modelo.id}>
                                                {modelo.name}
                                            </option>
                                        ))}
                                </select>
                                <ClearFilterButton filterValue={data.modelo} filterName="modelo" />
                            </div>
                        </div>

                        {/* Código Original */}
                        <div className="flex w-full flex-col gap-2">
                            <label htmlFor="codigo_original" className="text-[14px] md:text-[16px]">
                                Código Original
                            </label>
                            <div className="relative">
                                <input
                                    type="text"
                                    className="focus:outline-primary-orange w-full rounded-sm bg-white p-2 pr-10 text-sm outline-transparent transition duration-300 focus:outline md:text-base"
                                    id="codigo_original"
                                    name="codigo_original"
                                    placeholder="Ingrese código original"
                                    value={data.code}
                                    onChange={(e) => setData('code', e.target.value)}
                                />
                                <ClearFilterButton filterValue={data.code} filterName="code" />
                            </div>
                        </div>

                        {/* Código SR33 */}
                        <div className="flex w-full flex-col gap-2">
                            <label htmlFor="codigo_sr" className="text-[14px] md:text-[16px]">
                                Código SR33
                            </label>
                            <div className="relative">
                                <input
                                    type="text"
                                    className="focus:outline-primary-orange w-full rounded-sm bg-white p-2 pr-10 text-sm outline-transparent transition duration-300 focus:outline md:text-base"
                                    id="codigo_sr"
                                    name="codigo_sr"
                                    placeholder="Ingrese código sr33"
                                    value={data.code_sr}
                                    onChange={(e) => setData('code_sr', e.target.value)}
                                />
                                <ClearFilterButton filterValue={data.code_sr} filterName="code_sr" />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Botón de búsqueda */}
                <div className="flex w-full flex-col items-center justify-center gap-2 md:h-full md:w-fit md:items-end md:justify-end">
                    <button
                        type="submit"
                        className="bg-primary-orange hover:bg-primary-orange-dark w-full rounded-sm px-4 py-2 text-[14px] font-semibold text-white transition duration-300 md:w-auto md:text-[16px]"
                    >
                        Buscar
                    </button>
                </div>
            </form>
        </div>
    );
};

export default SearchBar;

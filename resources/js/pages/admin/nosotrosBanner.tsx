import { useForm, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import toast from 'react-hot-toast';
import Dashboard from './dashboard';

export default function Logos() {
    const { data, setData, processing, post } = useForm();

    const { nosotrosBanner } = usePage().props;

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        post(route('admin.nosotros.updateBanner'), {
            preserveScroll: true,
            onSuccess: () => {
                toast.success('Banner actualizado correctamente');
            },
            onError: (errors) => {
                toast.error('Error al actualizar banner');
                console.log(errors);
            },
        });
    };

    const MediaPicker = ({ name, label, previewUrl, data, setData }) => {
        const [localPreview, setLocalPreview] = useState(null);
        const file = data?.[name];

        useEffect(() => {
            if (file instanceof File) {
                const objUrl = URL.createObjectURL(file);
                setLocalPreview(objUrl);
                return () => URL.revokeObjectURL(objUrl);
            }
            setLocalPreview(null);
        }, [file]);

        const isVideoUrl = (url) => /\.(mp4|webm|ogg|m4v|mov)$/i.test(url ?? '');
        const fileIsVideo = file instanceof File && file.type?.startsWith('video/');
        const effectiveUrl = localPreview || previewUrl || '';
        const showVideo = fileIsVideo || isVideoUrl(effectiveUrl);

        return (
            <div className="col-span-2 flex flex-row justify-between gap-5">
                <div className="w-full">
                    <label htmlFor={name} className="block text-lg font-medium text-gray-900">
                        {label}
                    </label>
                    <div className="mt-2 flex justify-between rounded-lg border shadow-lg">
                        <div className="h-[200px] w-1/2 bg-[rgba(0,0,0,0.2)]">
                            {effectiveUrl ? (
                                showVideo ? (
                                    <video className="h-full w-full rounded-md object-cover" src={effectiveUrl} controls />
                                ) : (
                                    <img className="h-full w-full rounded-md object-cover" src={effectiveUrl} alt="" />
                                )
                            ) : (
                                <div className="flex h-full w-full items-center justify-center text-sm text-gray-500">Sin media</div>
                            )}
                        </div>
                        <div className="flex w-1/2 items-center justify-center">
                            <div className="h-fit items-center self-center text-center">
                                <div className="relative mt-4 flex flex-col items-center text-sm/6 text-gray-600">
                                    <label
                                        htmlFor={name}
                                        className="bg-primary-red relative cursor-pointer rounded-md px-2 py-1 font-semibold text-black"
                                    >
                                        <span>Cambiar Imagen/Video</span>
                                        <input
                                            id={name}
                                            name={name}
                                            onChange={(e) => setData(name, e.target.files?.[0])}
                                            type="file"
                                            accept="image/*,video/*"
                                            className="sr-only"
                                        />
                                    </label>
                                    <p className="absolute top-10 max-w-[200px] break-words">{file?.name}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    };

    return (
        <Dashboard>
            <div className="p-6">
                <form action="" onSubmit={handleSubmit}>
                    <div className="flex flex-row justify-between gap-5">
                        <div className="w-full">
                            <MediaPicker name="media" label="Imagen/Video (1366px Ancho) (655px Alto)" previewUrl={nosotrosBanner?.media} data={data} setData={setData} />
                        </div>
                    </div>

                    <div className="flex items-center justify-start gap-x-6 pt-10">
                        <button
                            type="submit"
                            disabled={processing}
                            className={`bg-primary-orange rounded-full px-3 py-2 text-sm font-semibold text-white shadow-sm transition-transform hover:scale-95 ${processing ? 'cursor-not-allowed opacity-70' : ''}`}
                        >
                            {processing ? 'Actualizando...' : 'Actualizar'}
                        </button>
                    </div>
                </form>
            </div>
        </Dashboard>
    );
}

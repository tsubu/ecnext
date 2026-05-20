import { Head, useForm } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function ConfirmPassword() {
    const { __ } = useTranslation();
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('password.confirm'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title={__('Confirm Password')} />

            <div className="mb-4 text-sm text-gray-600">
                {__('This is a secure area of the application. Please confirm your password before continuing.')}
            </div>

            <form onSubmit={submit}>
                <div className="mt-4">
                    <InputLabel htmlFor="password" value={__('Password')} />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        isFocused={true}
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4 flex items-center justify-end">
                    <PrimaryButton className="ms-4" disabled={processing}>
                        {__('Confirm')}
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}

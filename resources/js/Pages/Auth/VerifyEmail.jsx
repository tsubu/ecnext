import { Head, Link, useForm } from '@inertiajs/react';
import { useTranslation } from '@/Helpers/translations';

export default function VerifyEmail({ status }) {
    const { __ } = useTranslation();
    const { post, processing } = useForm({});

    const submit = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <GuestLayout>
            <Head title={__('Email Verification')} />

            <div className="mb-4 text-sm text-gray-600">
                {__("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.")}
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    {__('A new verification link has been sent to the email address you provided during registration.')}
                </div>
            )}

            <form onSubmit={submit}>
                <div className="mt-4 flex items-center justify-between">
                    <PrimaryButton disabled={processing}>
                        {__('Resend Verification Email')}
                    </PrimaryButton>

                    <Link
                        href={route('logout')}
                        method="post"
                        as="button"
                        className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        {__('Log Out')}
                    </Link>
                </div>
            </form>
        </GuestLayout>
    );
}

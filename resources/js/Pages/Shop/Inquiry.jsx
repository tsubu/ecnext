import React from 'react';
import { Head, useForm, usePage } from '@inertiajs/react';
import ShopLayout from '@/Layouts/ShopLayout';
import { useTranslation } from '@/Helpers/translations';

export default function Inquiry() {
    const { __ } = useTranslation();
    const { status } = usePage().props;
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        subject: '',
        message: '',
    });

    const submit = (e) => {
        e.preventDefault();
        post(route('inquiry.store'), {
            onSuccess: () => reset(),
        });
    };

    return (
        <ShopLayout>
            <Head title={`${__('Contact Us')} | EC-NEXT`} />
            

                <div className="grid grid-cols-1 md:grid-cols-3 gap-12">
                    {/* Contact Info Sidebar */}
                    <div className="md:col-span-1 space-y-8">
                        <div className="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-white">
                            <h3 className="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-6">{__('Contact Info')}</h3>
                            <div className="space-y-6">
                                <div className="flex gap-4">
                                    <div className="w-10 h-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 flex-shrink-0">
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div>
                                        <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{__('Email Us')}</p>
                                        <p className="text-sm font-bold text-slate-900">support@ec-next.com</p>
                                    </div>
                                </div>
                                <div className="flex gap-4">
                                    <div className="w-10 h-10 bg-rose-50 rounded-xl flex items-center justify-center text-rose-600 flex-shrink-0">
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <div>
                                        <p className="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">{__('Call Center')}</p>
                                        <p className="text-sm font-bold text-slate-900">0120-XXX-XXX</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl shadow-indigo-900/20 text-white relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-indigo-500/10 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                            <h3 className="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{__('Operating Hours')}</h3>
                            <p className="text-sm font-bold mb-1">Mon - Fri</p>
                            <p className="text-xs text-slate-400 mb-4">10:00 AM - 6:00 PM (JST)</p>
                            <p className="text-[10px] text-slate-500 italic">*{__('Excluding holidays')}</p>
                        </div>
                    </div>

                    {/* Contact Form */}
                    <div className="md:col-span-2">
                        <form onSubmit={submit} className="bg-white p-10 rounded-[3rem] shadow-xl shadow-slate-200/50 border border-white space-y-8">
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{__('Full Name')}</label>
                                    <input 
                                        type="text" 
                                        value={data.name}
                                        onChange={e => setData('name', e.target.value)}
                                        placeholder={__('e.g. Taro Yamada')}
                                        className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all"
                                        required
                                    />
                                    {errors.name && <p className="text-[10px] font-bold text-rose-500 mt-1 ml-1">{errors.name}</p>}
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{__('Email Address')}</label>
                                    <input 
                                        type="email" 
                                        value={data.email}
                                        onChange={e => setData('email', e.target.value)}
                                        placeholder="taro@example.com"
                                        className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all"
                                        required
                                    />
                                    {errors.email && <p className="text-[10px] font-bold text-rose-500 mt-1 ml-1">{errors.email}</p>}
                                </div>
                            </div>

                            <div className="space-y-2">
                                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{__('Subject')}</label>
                                <input 
                                    type="text" 
                                    value={data.subject}
                                    onChange={e => setData('subject', e.target.value)}
                                    placeholder={__('What can we help you with?')}
                                    className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-2xl text-slate-900 font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all"
                                    required
                                />
                                {errors.subject && <p className="text-[10px] font-bold text-rose-500 mt-1 ml-1">{errors.subject}</p>}
                            </div>

                            <div className="space-y-2">
                                <label className="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">{__('Message Content')}</label>
                                <textarea 
                                    rows="6"
                                    value={data.message}
                                    onChange={e => setData('message', e.target.value)}
                                    placeholder={__('Type your message here...')}
                                    className="w-full px-6 py-4 bg-slate-50 border border-slate-100 rounded-[2rem] text-slate-900 font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all resize-none"
                                    required
                                ></textarea>
                                {errors.message && <p className="text-[10px] font-bold text-rose-500 mt-1 ml-1">{errors.message}</p>}
                            </div>

                            <button 
                                type="submit" 
                                disabled={processing}
                                className="w-full py-5 bg-indigo-600 text-white font-black uppercase tracking-[0.2em] rounded-2xl transition-all shadow-xl shadow-indigo-600/30 hover:bg-indigo-700 active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-3"
                            >
                                {processing ? (
                                    <svg className="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle><path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                ) : (
                                    <>
                                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2.5" d="M12 19l7-7-7-7m5 7H3"/></svg>
                                        {__('Send Inquiry')}
                                    </>
                                )}
                            </button>
                        </form>
                    </div>
                </div>
        </ShopLayout>
    );
}

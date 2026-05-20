@extends('admin.layouts.base')

@section('title', __('Edit Customer'))
@section('page_title', __('Customer Management'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.customers.index') }}" class="p-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-2xl transition-all border border-white/5 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight">{{ __('Edit Customer') }}: {{ $customer->name }}</h3>
        <p class="text-sm text-slate-400">{{ __('Update member profile and account status') }}</p>
    </div>
</div>

<form action="{{ route('admin.customers.update', $customer) }}" method="POST" class="max-w-4xl grid grid-cols-1 lg:grid-cols-3 gap-8">
    @csrf
    @method('PUT')
    
    <div class="lg:col-span-2 space-y-8">
        <!-- Basic Info -->
        <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Identity Information') }}</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Full Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $customer->name) }}" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    @error('name') <p class="text-rose-400 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-slate-300 ml-1">{{ __('Email Address') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $customer->email) }}" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    @error('email') <p class="text-rose-400 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="phone" class="text-sm font-bold text-slate-300 ml-1">{{ __('Phone Number') }}</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $customer->phone) }}"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                        placeholder="090-1234-5678">
                </div>
            </div>
        </div>

        <!-- Address Info -->
        <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Shipping Address') }}</h4>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="space-y-2">
                    <label for="postal_code" class="text-sm font-bold text-slate-300 ml-1">{{ __('Postal Code') }}</label>
                    <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $customer->postal_code) }}"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                        placeholder="{{ __('123-4567') }}">
                </div>
            </div>

            <div class="space-y-2">
                <label for="address1" class="text-sm font-bold text-slate-300 ml-1">{{ __('Address Line 1') }}</label>
                <input type="text" name="address1" id="address1" value="{{ old('address1', $customer->address1) }}"
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                    placeholder="{{ __('example_address_line1') }}">
            </div>

            <div class="space-y-2">
                <label for="address2" class="text-sm font-bold text-slate-300 ml-1">{{ __('Address Line 2 (Building)') }}</label>
                <input type="text" name="address2" id="address2" value="{{ old('address2', $customer->address2) }}"
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all"
                    placeholder="{{ __('example_address_line2') }}">
            </div>
        </div>

        <!-- Security -->
        <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
            <div class="flex justify-between items-center">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Update Security') }}</h4>
                <span class="px-3 py-1 bg-white/5 text-slate-500 text-[10px] font-black rounded-lg border border-white/5 tracking-widest uppercase">{{ __('Optional') }}</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="password" class="text-sm font-bold text-slate-300 ml-1">{{ __('New Password') }}</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-bold text-slate-300 ml-1">{{ __('Confirm New Password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar / Status -->
    <div class="space-y-8">
        <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6 sticky top-24">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Account Status') }}</h4>
            
            <div class="space-y-4">
                @php $status = old('status', $customer->status); @endphp
                <label class="flex items-center gap-4 p-4 border rounded-2xl transition-all cursor-pointer {{ $status === 'active' ? 'bg-emerald-500/10 border-emerald-500/50 text-emerald-400' : 'bg-white/5 border-white/10 text-slate-500 hover:border-white/20' }}">
                    <input type="radio" name="status" value="active" class="hidden" {{ $status === 'active' ? 'checked' : '' }}>
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $status === 'active' ? 'border-emerald-500' : 'border-slate-700' }}">
                        @if($status === 'active') <div class="w-2 h-2 bg-emerald-500 rounded-full"></div> @endif
                    </div>
                    <span class="font-bold text-sm">{{ __('Active') }}</span>
                </label>

                <label class="flex items-center gap-4 p-4 border rounded-2xl transition-all cursor-pointer {{ $status === 'pending' ? 'bg-amber-500/10 border-amber-500/50 text-amber-400' : 'bg-white/5 border-white/10 text-slate-500 hover:border-white/20' }}">
                    <input type="radio" name="status" value="pending" class="hidden" {{ $status === 'pending' ? 'checked' : '' }}>
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $status === 'pending' ? 'border-amber-500' : 'border-slate-700' }}">
                        @if($status === 'pending') <div class="w-2 h-2 bg-amber-500 rounded-full"></div> @endif
                    </div>
                    <span class="font-bold text-sm">{{ __('Pending') }}</span>
                </label>

                <label class="flex items-center gap-4 p-4 border rounded-2xl transition-all cursor-pointer {{ $status === 'restricted' ? 'bg-rose-500/10 border-rose-500/50 text-rose-400' : 'bg-white/5 border-white/10 text-slate-500 hover:border-white/20' }}">
                    <input type="radio" name="status" value="restricted" class="hidden" {{ $status === 'restricted' ? 'checked' : '' }}>
                    <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center {{ $status === 'restricted' ? 'border-rose-500' : 'border-slate-700' }}">
                        @if($status === 'restricted') <div class="w-2 h-2 bg-rose-500 rounded-full"></div> @endif
                    </div>
                    <span class="font-bold text-sm">{{ __('Restricted') }}</span>
                </label>
            </div>

            <div class="pt-6 space-y-4">
                <button type="submit" class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                    {{ __('Save Changes') }}
                </button>
                <a href="{{ route('admin.customers.index') }}" class="block w-full py-4 text-center bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white font-bold rounded-2xl transition-all border border-white/5">
                    {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

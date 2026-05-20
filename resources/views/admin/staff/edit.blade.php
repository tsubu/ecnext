@extends('admin.layouts.base')

@section('title', __('Edit Staff Member'))
@section('page_title', __('Staff Management'))

@section('content')
<div class="mb-8 flex items-center gap-4">
    <a href="{{ route('admin.staff.index') }}" class="p-3 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white rounded-2xl transition-all border border-white/5 group">
        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <div>
        <h3 class="text-xl font-bold tracking-tight">{{ __('Edit Staff Member') }}: {{ $staff->name }}</h3>
        <p class="text-sm text-slate-400">{{ __('Update administrative account details and permissions') }}</p>
    </div>
</div>

<form action="{{ route('admin.staff.update', $staff) }}" method="POST" class="max-w-2xl">
    @csrf
    @method('PUT')
    
    <div class="glass p-8 rounded-[2rem] shadow-2xl border border-white/5 space-y-6">
        <!-- Basic Info Section -->
        <div class="space-y-4">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Account Information') }}</h4>
            
            <div class="grid grid-cols-1 gap-6">
                <!-- Name -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-bold text-slate-300 ml-1">{{ __('Full Name') }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $staff->name) }}" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600">
                    @error('name') <p class="text-rose-400 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <!-- Email -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-bold text-slate-300 ml-1">{{ __('Email Address') }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $staff->email) }}" required
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-600">
                    @error('email') <p class="text-rose-400 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <hr class="border-white/5">

        <!-- Role Section -->
        <div class="space-y-4">
            <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em] mb-4">{{ __('Permissions & Access') }}</h4>
            <div class="space-y-2">
                <label for="admin_role_id" class="text-sm font-bold text-slate-300 ml-1">{{ __('Assign Role') }}</label>
                <select name="admin_role_id" id="admin_role_id" required
                    class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all appearance-none cursor-pointer">
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('admin_role_id', $staff->admin_role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->display_name ?? $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('admin_role_id') <p class="text-rose-400 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <hr class="border-white/5">

        <!-- Password Section (Optional) -->
        <div class="space-y-4">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-xs font-black text-indigo-400 uppercase tracking-[0.2em]">{{ __('Update Security') }}</h4>
                <span class="px-3 py-1 bg-white/5 text-slate-500 text-[10px] font-black rounded-lg border border-white/5">{{ __('Optional') }}</span>
            </div>
            <p class="text-xs text-slate-400 mb-4">{{ __('Leave blank if you do not want to change the password.') }}</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label for="password" class="text-sm font-bold text-slate-300 ml-1">{{ __('New Password') }}</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                    @error('password') <p class="text-rose-400 text-xs font-bold mt-1 ml-1">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-bold text-slate-300 ml-1">{{ __('Confirm New Password') }}</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-5 py-4 bg-white/5 border border-white/10 rounded-2xl text-white font-medium focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 outline-none transition-all">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="pt-6 flex gap-4">
            <button type="submit" class="flex-1 px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/20 active:scale-95">
                {{ __('Update Staff Profile') }}
            </button>
            <a href="{{ route('admin.staff.index') }}" class="px-8 py-4 bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white font-bold rounded-2xl transition-all border border-white/5">
                {{ __('Cancel') }}
            </a>
        </div>
    </div>
</form>

@if(auth('admin')->id() !== $staff->id)
<div class="mt-12 max-w-2xl p-8 bg-rose-500/5 border border-rose-500/10 rounded-[2rem]">
    <div class="flex items-center gap-4 mb-4">
        <div class="w-10 h-10 rounded-xl bg-rose-500/10 flex items-center justify-center">
            <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div>
            <h4 class="text-lg font-bold text-rose-500">{{ __('Danger Zone') }}</h4>
            <p class="text-sm text-slate-500">{{ __('Permanently remove this administrator account') }}</p>
        </div>
    </div>
    <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this administrator?') }}')">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full py-4 bg-rose-500/10 hover:bg-rose-500 text-rose-500 hover:text-white font-bold rounded-2xl transition-all border border-rose-500/20">
            {{ __('Delete Administrator Account') }}
        </button>
    </form>
</div>
@endif
@endsection

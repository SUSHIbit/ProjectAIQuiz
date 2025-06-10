@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-300 focus:border-slate-500 focus:ring-slate-500 rounded-lg shadow-sm transition-all duration-200']) !!}>
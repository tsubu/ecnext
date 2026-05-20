{{-- 統合: フリーテキスト + HTML埋め込み --}}
@if(!empty($settings['content']))
<div class="free-content-block" data-aos="fade-up">
    <div class="prose prose-slate prose-indigo max-w-none">
        {!! $settings['content'] !!}
    </div>
</div>
@endif

{{-- カスタムCSS: ページ固有のスタイルを <style> タグとして埋め込み --}}
@if(!empty($settings['css_code']))
<style>
{!! $settings['css_code'] !!}
</style>
@endif

@if ($block)
  @php
    $layout = isset($block->json_params->layout) && $block->json_params->layout != '' ? $block->json_params->layout : 'default';
  @endphp


  @if (\View::exists('frontend.blocks.' . $block->block_code . '.layout.' . $layout))

    @include('frontend.blocks.' . $block->block_code . '.layout.' . $layout)
  @else
    {{ 'Style: frontend.blocks.' . $block->block_code . '.layout.' . $layout . ' do not exists!' }}
  @endif

@endif

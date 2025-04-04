<li>
  <label>
    @if (in_array($menu->id, $selectedMenus) &&
            (isset($admin->permission_access_by_role->menu_id) &&
                in_array($menu->id, $admin->permission_access_by_role->menu_id)))
      <input type="checkbox" checked disabled>
      <span style="font-style: italic;" class="text-danger">
        {{ $menu->name }}
      </span>
    @else
      <input type="checkbox" name="json_params[menu_id][]" value="{{ $menu->id }}"
        {{ in_array($menu->id, $selectedMenus) ? 'checked' : '' }}>
      <span>
        {{ $menu->name }}
      </span>
    @endif

  </label>

  @if ($menu->children->isNotEmpty())
    <ul>
      @foreach ($menu->children as $child)
        @include('admin.pages.admins.role-menu-item', [
            'menu' => $child,
            'selectedMenus' => $selectedMenus,
        ])
      @endforeach
    </ul>
  @endif
</li>

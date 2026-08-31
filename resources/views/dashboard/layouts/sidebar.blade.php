  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          @foreach ($menus as $item)

              @if ($item->header == 1)
                  <li class="nav-heading">{{ $item->name }}</li>
              @else
                  @php
                      // cek apakah salah satu child aktif
                      $isActive = $item->children
                          ->pluck('route')
                          ->filter()
                          ->contains(fn($route) => request()->routeIs($route . '*'));
                  @endphp



                  @if ($item->children->count())
                      <li class="nav-item">
                          <a class="nav-link {{ $isActive ? '' : 'collapsed' }}" data-bs-toggle="collapse"
                              data-bs-target="#menu-{{ $item->id }}" href="#">
                              <i class="{{ $item->icon }}"></i>
                              <span>{{ $item->name }}</span>
                              <i class="bi bi-chevron-down ms-auto"></i>
                          </a>

                          <ul id="menu-{{ $item->id }}" class="nav-content collapse {{ $isActive ? 'show' : '' }}"
                              data-bs-parent="#sidebar-nav">

                              @foreach ($item->children as $child)
                                  <li>
                                      <a style="text-decoration: none;"
                                          href="{{ Route::has($child->route) && !in_array($child->route, ['#', '/', ' ']) ? route($child->route) : '#' }}"
                                          class="{{ request()->routeIs($child->route . '*') ? 'active' : '' }}">
                                          <i class="{{ $child->icon }}"></i>
                                          <span>{{ $child->name }}</span>
                                      </a>
                                  </li>
                              @endforeach

                          </ul>
                      </li>
                  @else

                      <li class="nav-item">
                          <a class="nav-link {{ request()->routeIs($item->route . '*') ? 'active' : 'collapsed' }}"
                              href="{{ Route::has($item->route) && !in_array($item->route, ['#', '/', ' ']) ? route($item->route) : '#' }}">
                              <i class="{{ $item->icon }}"></i>
                              <span>{{ $item->name }}</span>
                          </a>
                      </li>
                  @endif
              @endif
          @endforeach

      </ul>

  </aside><!-- End Sidebar-->

@if ($paginator->hasPages())
    
    @foreach ($elements as $element)
        @php
        // dd($element);
        @endphp
        @if (is_string($element))
            <li class="pagination-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
        @endif
        @if (is_array($element))
            
            @foreach ($element as $page => $url)
                

                @if ($page == $paginator->currentPage())
                    <li>
                      <a href="#" class="selected">
                        <i class="fas fa-search"></i>
                        <span>
                            {{ ($page*150)-150+1 . '-' . ($page*150)  }}
                        </span>
                      </a>
                    </li>
                @else
                    <li>
                      <a href="{{ $url }}" class="hvr-sweep-to-right">
                        <i class="fas fa-search color-blue4"></i>
                        <span>
                            {{ ($page*150)-150+1 . '-' . ($page*150)  }}
                        </span>
                      </a>
                    </li>
                @endif


            @endforeach
        @endif
    @endforeach
    
@endif

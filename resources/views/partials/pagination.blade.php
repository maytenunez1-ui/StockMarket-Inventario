@if ($paginator->hasPages())
    <div class="pagination">
        @if ($paginator->onFirstPage())
            <span class="pagination-link is-disabled">Anterior</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-link">Anterior</a>
        @endif

        <span class="pagination-meta">Pagina {{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</span>

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-link">Siguiente</a>
        @else
            <span class="pagination-link is-disabled">Siguiente</span>
        @endif
    </div>
@endif

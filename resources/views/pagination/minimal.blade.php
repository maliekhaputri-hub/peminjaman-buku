<div class="minimal-pagination">
    @if ($paginator->hasPages())
        <div class="pagination-buttons">
            @if (!$paginator->onFirstPage())
                <a href="{{ $paginator->previousPageUrl() }}" class="page-link prev" rel="prev">Previous</a>
            @endif
            
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="page-link next" rel="next">Next</a>
            @endif
        </div>
    @endif
</div>

<style>
.minimal-pagination {
    display: flex;
    justify-content: center;
    gap: 1.5rem;
    margin: 3rem 0;
}

.pagination-buttons {
    display: flex;
    gap: 1rem;
}

.page-link {
    padding: 1rem 2rem;
    background: linear-gradient(135deg, #e2b8e0, #ecd4e3);
    color: #2C3E50;
    border: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1rem;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 8px 25px rgba(226, 184, 224, 0.4);
    letter-spacing: 0.5px;
    min-width: 120px;
    text-transform: uppercase;
    font-size: 0.95rem;
}

.page-link:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(226, 184, 224, 0.6);
    background: linear-gradient(135deg, #FF8E8E, #e2b8e0);
    color: white;
}
</style>

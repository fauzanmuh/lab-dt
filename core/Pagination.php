<?php

namespace Core;

class Pagination
{
    protected int $currentPage;
    protected int $perPage;
    protected int $totalItems;
    protected int $totalPages;

    public function __construct(int $totalItems, int $perPage = 10, int $currentPage = 1)
    {
        $this->totalItems = $totalItems;
        $this->perPage = $perPage;
        $this->currentPage = max(1, $currentPage);
        $this->totalPages = ceil($totalItems / $perPage);

        // Ensure current page isn't greater than total pages (unless total is 0)
        if ($this->totalPages > 0 && $this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        }
    }

    public function getOffset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function getLimit(): int
    {
        return $this->perPage;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    public function hasPages(): bool
    {
        return $this->totalPages > 1;
    }

    /**
     * Generate pagination links HTML (Bootstrap 5)
     * 
     * @param string $baseUrl The base URL for links (e.g., '/admin/news')
     * @param array $queryParams Additional query parameters to preserve
     */
    public function render(string $baseUrl, array $queryParams = []): string
    {
        if (!$this->hasPages()) {
            return '';
        }

        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';

        // Previous Link
        $prevDisabled = $this->currentPage <= 1 ? 'disabled' : '';
        $prevUrl = $this->buildUrl($baseUrl, $this->currentPage - 1, $queryParams);
        $html .= sprintf(
            '<li class="page-item %s"><a class="page-link" href="%s" %s>Previous</a></li>',
            $prevDisabled,
            $prevUrl,
            $prevDisabled ? 'tabindex="-1" aria-disabled="true"' : ''
        );

        // Page Numbers
        // Simple implementation: show all pages if <= 7, otherwise show window
        // For brevity, let's implement a simple window: First ... Prev 2 [Current] Next 2 ... Last

        $start = max(1, $this->currentPage - 2);
        $end = min($this->totalPages, $this->currentPage + 2);

        if ($start > 1) {
            $html .= $this->renderPageItem($baseUrl, 1, $queryParams);
            if ($start > 2) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $active = $i === $this->currentPage ? 'active' : '';
            $html .= $this->renderPageItem($baseUrl, $i, $queryParams, $active);
        }

        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            $html .= $this->renderPageItem($baseUrl, $this->totalPages, $queryParams);
        }

        // Next Link
        $nextDisabled = $this->currentPage >= $this->totalPages ? 'disabled' : '';
        $nextUrl = $this->buildUrl($baseUrl, $this->currentPage + 1, $queryParams);
        $html .= sprintf(
            '<li class="page-item %s"><a class="page-link" href="%s" %s>Next</a></li>',
            $nextDisabled,
            $nextUrl,
            $nextDisabled ? 'tabindex="-1" aria-disabled="true"' : ''
        );

        $html .= '</ul></nav>';

        return $html;
    }

    protected function renderPageItem($baseUrl, $page, $queryParams, $activeClass = '')
    {
        $url = $this->buildUrl($baseUrl, $page, $queryParams);
        return sprintf(
            '<li class="page-item %s"><a class="page-link" href="%s">%d</a></li>',
            $activeClass,
            $url,
            $page
        );
    }

    protected function buildUrl($baseUrl, $page, $queryParams)
    {
        $queryParams['page'] = $page;
        return $baseUrl . '?' . http_build_query($queryParams);
    }

    /**
     * Generate custom public pagination links HTML
     */
    public function renderPublic(string $baseUrl, array $queryParams = []): string
    {
        if (!$this->hasPages()) {
            return '';
        }

        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center align-items-center mx-auto mt-4 p-2 rounded-3 custom-pagination" style="width: fit-content; background-color: #F0F0F0;">';

        // Previous Link
        $prevUrl = $this->currentPage > 1 ? $this->buildUrl($baseUrl, $this->currentPage - 1, $queryParams) : '#';
        $prevDisabled = $this->currentPage <= 1 ? 'disabled' : ''; // Bootstrap disabled class might not work with custom style, but keeping structure
        // For custom style, maybe just remove href or add class? 
        // The image shows it enabled. If disabled, maybe just keep it but non-clickable?
        // Let's use standard behavior.

        $html .= sprintf(
            '<li class="page-item %s"><a class="page-link arrow" href="%s"><i class="bi bi-chevron-left"></i></a></li>',
            $prevDisabled,
            $prevUrl
        );

        // Page Numbers
        $start = max(1, $this->currentPage - 2);
        $end = min($this->totalPages, $this->currentPage + 2);

        if ($start > 1) {
            $html .= $this->renderPublicPageItem($baseUrl, 1, $queryParams);
            if ($start > 2) {
                $html .= '<li class="page-item"><span class="page-link" style="background: transparent; border: none;">....</span></li>';
            }
        }

        for ($i = $start; $i <= $end; $i++) {
            $active = $i === $this->currentPage ? 'active' : '';
            $html .= $this->renderPublicPageItem($baseUrl, $i, $queryParams, $active);
        }

        if ($end < $this->totalPages) {
            if ($end < $this->totalPages - 1) {
                $html .= '<li class="page-item"><span class="page-link" style="background: transparent; border: none;">....</span></li>';
            }
            $html .= $this->renderPublicPageItem($baseUrl, $this->totalPages, $queryParams);
        }

        // Next Link
        $nextUrl = $this->currentPage < $this->totalPages ? $this->buildUrl($baseUrl, $this->currentPage + 1, $queryParams) : '#';
        $nextDisabled = $this->currentPage >= $this->totalPages ? 'disabled' : '';

        $html .= sprintf(
            '<li class="page-item %s"><a class="page-link arrow arrow-next" href="%s"><i class="bi bi-chevron-right"></i></a></li>',
            $nextDisabled,
            $nextUrl
        );

        $html .= '</ul></nav>';

        return $html;
    }

    protected function renderPublicPageItem($baseUrl, $page, $queryParams, $activeClass = '')
    {
        $url = $this->buildUrl($baseUrl, $page, $queryParams);
        return sprintf(
            '<li class="page-item %s"><a class="page-link" href="%s">%d</a></li>',
            $activeClass,
            $url,
            $page
        );
    }
}

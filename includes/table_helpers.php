<?php
/**
 * Table and pagination helper functions for list pages.
 * Usage: include this file and call render_table($headers, $rows, $cellRenderer, $rowAttrs) and render_pagination($page, $totalPages, $pageSize, $baseUrl)
 *
 * For action columns (edit/delete), generate the action HTML in your controller and add to the $rows array, or use the $cellRenderer callback.
 * Privilege checks for actions should be done before passing data to these helpers.
 */

/**
 * Render a Bootstrap table.
 *
 * @param array $headers Array of column headers (keys)
 * @param array $rows Array of associative arrays (each row)
 * @param callable|null $cellRenderer Optional callback: function($row, $key, $value): string (returns HTML for cell)
 * @param array $rowAttrs Optional array of row attributes (associative, keyed by row index)
 * @return void
 */
function render_table(array $headers, array $rows, callable $cellRenderer = null, array $rowAttrs = []): void {
    echo '<div class="table-responsive">';
    echo '<table class="table table-bordered table-hover mb-0">';
    echo '<thead><tr>';
    foreach ($headers as $header) {
        echo '<th>' . htmlspecialchars($header) . '</th>';
    }
    echo '</tr></thead><tbody>';
    foreach ($rows as $i => $row) {
        $attrs = isset($rowAttrs[$i]) ? ' ' . htmlspecialchars($rowAttrs[$i]) : '';
        echo '<tr' . $attrs . '>';
        foreach ($headers as $key) {
            $value = $row[$key] ?? '';
            if ($cellRenderer) {
                echo $cellRenderer($row, $key, $value);
            } else {
                if ($key === 'ID') {
                    // Render ID column as raw HTML (for links)
                    echo '<td>' . $value . '</td>';
                } else {
                    echo '<td>' . htmlspecialchars($value) . '</td>';
                }
            }
        }
        echo '</tr>';
    }
    echo '</tbody></table></div>';
}

/**
 * Render a Bootstrap pagination bar.
 *
 * @param int $page Current page number
 * @param int $totalPages Total number of pages
 * @param int $pageSize Current page size
 * @param string $baseUrl Base URL for pagination links (should include other GET params except page/pageSize)
 * @return void
 */
function render_pagination(int $page, int $totalPages, int $pageSize, string $baseUrl): void {
    if ($totalPages <= 1) return;
    echo '<nav aria-label="Pagination" class="mt-3">';
    echo '<ul class="pagination justify-content-center">';
    for ($i = 1; $i <= $totalPages; $i++) {
        $active = ($i === $page) ? ' active' : '';
        echo '<li class="page-item' . $active . '"><a class="page-link" href="' . htmlspecialchars($baseUrl) . '&page=' . $i . '&pageSize=' . $pageSize . '">' . $i . '</a></li>';
    }
    echo '</ul></nav>';
}

document.addEventListener('DOMContentLoaded', function () {
    initializeNewsLiveSearch(document);
});

function initializeNewsLiveSearch(scope) {
    var newsRoot = scope.querySelector('[data-news-live-search-root]');
    if (!newsRoot) {
        return;
    }

    var form = newsRoot.querySelector('[data-news-search-form]');
    var input = newsRoot.querySelector('[data-news-search-input]');
    var filters = newsRoot.querySelectorAll('[data-news-search-filter]');
    var clearButton = newsRoot.querySelector('[data-news-search-clear]');
    var status = newsRoot.querySelector('[data-news-search-status]');

    if (!form || !input) {
        return;
    }

    var baseUrl = newsRoot.getAttribute('data-news-url') || form.getAttribute('action') || window.location.pathname;
    var debounceTimer = null;
    var activeController = null;
    var lastState = getFormState();
    var requestCounter = 0;

    function setStatus(message) {
        if (status) {
            status.textContent = message;
        }
    }

    function setLoadingState(isLoading) {
        newsRoot.classList.toggle('is-loading', isLoading);
        newsRoot.setAttribute('aria-busy', isLoading ? 'true' : 'false');
    }

    function getFormState() {
        var formData = new FormData(form);

        return {
            keyword: (formData.get('keyword') || '').toString().trim(),
            category: (formData.get('category') || '').toString().trim(),
            sort: (formData.get('sort') || 'latest').toString().trim() || 'latest'
        };
    }

    function buildSearchUrl(state) {
        var url = new URL(baseUrl, window.location.origin);

        if (state.keyword !== '') {
            url.searchParams.set('keyword', state.keyword);
        } else {
            url.searchParams.delete('keyword');
        }

        if (state.category !== '') {
            url.searchParams.set('category', state.category);
        } else {
            url.searchParams.delete('category');
        }

        if (state.sort !== '' && state.sort !== 'latest') {
            url.searchParams.set('sort', state.sort);
        } else {
            url.searchParams.delete('sort');
        }

        url.searchParams.delete('page');
        return url;
    }

    function replaceNewsRoot(htmlText, targetUrl, shouldRestoreFocus) {
        var parser = new DOMParser();
        var nextDocument = parser.parseFromString(htmlText, 'text/html');
        var nextRoot = nextDocument.querySelector('[data-news-live-search-root]');

        if (!nextRoot) {
            window.location.href = targetUrl;
            return;
        }

        newsRoot.replaceWith(nextRoot);
        document.title = nextDocument.title || document.title;
        window.history.replaceState({}, '', targetUrl);
        initializeNewsLiveSearch(document);

        if (shouldRestoreFocus) {
            var nextInput = document.querySelector('[data-news-search-input]');
            if (nextInput) {
                nextInput.focus({ preventScroll: true });
                var end = nextInput.value.length;
                nextInput.setSelectionRange(end, end);
            }
        }
    }

    function sameState(left, right) {
        return left.keyword === right.keyword
            && left.category === right.category
            && left.sort === right.sort;
    }

    function performSearch(nextState, options) {
        var settings = options || {};
        var shouldRestoreFocus = settings.restoreFocus !== false;
        var normalizedState = {
            keyword: (nextState.keyword || '').trim(),
            category: nextState.category || '',
            sort: nextState.sort || 'latest'
        };

        if (sameState(normalizedState, lastState) && !settings.force) {
            setStatus(
                normalizedState.keyword === '' && normalizedState.category === '' && normalizedState.sort === 'latest'
                    ? 'Gõ từ khóa hoặc thay đổi bộ lọc để cập nhật danh sách bài viết.'
                    : 'Kết quả đã được cập nhật theo bộ lọc hiện tại.'
            );
            return;
        }

        lastState = normalizedState;
        requestCounter += 1;
        var currentRequest = requestCounter;
        var targetUrl = buildSearchUrl(normalizedState);

        if (activeController) {
            activeController.abort();
        }

        activeController = new AbortController();
        setLoadingState(true);
        setStatus(
            normalizedState.keyword === '' && normalizedState.category === '' && normalizedState.sort === 'latest'
                ? 'Đang tải lại toàn bộ bài viết...'
                : 'Đang áp dụng tìm kiếm và bộ lọc...'
        );

        fetch(targetUrl.toString(), {
            signal: activeController.signal,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Request failed');
                }

                return response.text();
            })
            .then(function (htmlText) {
                if (currentRequest !== requestCounter) {
                    return;
                }

                replaceNewsRoot(htmlText, targetUrl.toString(), shouldRestoreFocus);
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }

                setLoadingState(false);
                setStatus('Không thể tải kết quả realtime. Trang sẽ chuyển sang chế độ tìm kiếm thường.');
                window.location.href = targetUrl.toString();
            });
    }

    input.addEventListener('input', function (event) {
        clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(function () {
            var state = getFormState();
            state.keyword = event.target.value.trim();
            performSearch(state, { restoreFocus: true });
        }, 350);
    });

    filters.forEach(function (filter) {
        filter.addEventListener('change', function () {
            clearTimeout(debounceTimer);
            performSearch(getFormState(), { restoreFocus: false, force: true });
        });
    });

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        clearTimeout(debounceTimer);
        performSearch(getFormState(), { restoreFocus: true, force: true });
    });

    if (clearButton) {
        clearButton.addEventListener('click', function (event) {
            event.preventDefault();
            form.reset();
            input.value = '';

            filters.forEach(function (filter) {
                if (filter.name === 'category' || filter.name === 'sort') {
                    filter.value = filter.name === 'sort' ? 'latest' : '';
                }
            });

            clearTimeout(debounceTimer);
            performSearch({ keyword: '', category: '', sort: 'latest' }, { restoreFocus: true, force: true });
        });
    }
}

(function () {
  if (typeof window.wp === 'undefined' || !window.GIIThemeConfig) {
    return;
  }

  const { __ } = window.wp.i18n;
  const apiFetch = window.wp.apiFetch;
  apiFetch.use(window.wp.apiFetch.createNonceMiddleware(GIIThemeConfig.nonce));

  const dashboardEl = document.getElementById('gii-dashboard');
  if (dashboardEl) {
    const panels = dashboardEl.querySelectorAll('.dashboard-panel');
    const navButtons = dashboardEl.querySelectorAll('.dashboard-nav button');

    const showPanel = (slug) => {
      panels.forEach((panel) => {
        if (panel.dataset.panel === slug) {
          panel.hidden = false;
          panel.innerHTML = `<p>${GIIThemeConfig.strings.loading}</p>`;
          if (slug === 'products') {
            fetchProducts(panel);
          }
          if (slug === 'invoices') {
            fetchInvoices(panel);
          }
          if (slug === 'account') {
            fetchAccount(panel);
          }
        } else {
          panel.hidden = true;
        }
      });
    };

    navButtons.forEach((button) => {
      button.addEventListener('click', () => {
        navButtons.forEach((btn) => btn.classList.remove('active'));
        button.classList.add('active');
        showPanel(button.dataset.tab);
      });
    });

    const fetchProducts = async (panel) => {
      try {
        const products = await apiFetch({ path: '/gii-saas/v1/products' });
        if (!Array.isArray(products) || !products.length) {
          panel.innerHTML = `<p>${GIIThemeConfig.strings.noProducts}</p>`;
          return;
        }
        panel.innerHTML = `<table class="table"><thead><tr><th>${__('Name', 'surajx-gii-theme')}</th><th>${__('Price', 'surajx-gii-theme')}</th><th>${__('GST Rate', 'surajx-gii-theme')}</th></tr></thead><tbody>${products
          .map(
            (item) =>
              `<tr><td>${item.name}</td><td>${item.price}</td><td>${item.gst_rate}%</td></tr>`
          )
          .join('')}</tbody></table>`;
      } catch (error) {
        console.error('Products error', error);
        panel.innerHTML = `<p>${__('Unable to load products.', 'surajx-gii-theme')}</p>`;
      }
    };

    const fetchInvoices = async (panel) => {
      try {
        const invoices = await apiFetch({ path: '/gii-saas/v1/invoices' });
        if (!Array.isArray(invoices) || !invoices.length) {
          panel.innerHTML = `<p>${GIIThemeConfig.strings.noInvoices}</p>`;
          return;
        }
        panel.innerHTML = `<div class="invoice-list">${invoices
          .map(
            (invoice) =>
              `<article class="card">
                <h3>${invoice.invoice_number}</h3>
                <p>${__('Customer', 'surajx-gii-theme')}: ${invoice.customer_name}</p>
                <p>${__('Total', 'surajx-gii-theme')}: ${invoice.total_amount}</p>
                <a class="btn btn-outline" href="${invoice.pdf_url}" target="_blank" rel="noopener noreferrer">${__('Download PDF', 'surajx-gii-theme')}</a>
              </article>`
          )
          .join('')}</div>`;
      } catch (error) {
        console.error('Invoice error', error);
        panel.innerHTML = `<p>${__('Unable to load invoices.', 'surajx-gii-theme')}</p>`;
      }
    };

    const fetchAccount = async (panel) => {
      try {
        const account = await apiFetch({ path: '/gii-saas/v1/account' });
        panel.innerHTML = `
          <div class="card">
            <h3>${__('Account Summary', 'surajx-gii-theme')}</h3>
            <p>${__('Plan', 'surajx-gii-theme')}: ${account.plan}</p>
            <p>${__('Invoices Generated', 'surajx-gii-theme')}: ${account.invoice_usage}</p>
            <a class="btn btn-primary" href="${GIIThemeConfig.oauthUrl}">
              <span>${GIIThemeConfig.strings.oauthButtonText}</span>
            </a>
          </div>`;
      } catch (error) {
        console.error('Account error', error);
        panel.innerHTML = `<p>${__('Unable to load account details.', 'surajx-gii-theme')}</p>`;
      }
    };

    showPanel('products');
  }

  const invoiceForm = document.getElementById('gii-invoice-builder');
  if (invoiceForm) {
    const linesContainer = document.getElementById('gii-product-lines');
    const resultEl = document.getElementById('gii-invoice-result');

    const renderLine = () => {
      const group = document.createElement('div');
      group.className = 'gii-line card';
      group.innerHTML = `
        <label>
          <span>${__('Product', 'surajx-gii-theme')}</span>
          <input type="text" name="product_name[]" required>
        </label>
        <label>
          <span>${__('Price', 'surajx-gii-theme')}</span>
          <input type="number" step="0.01" min="0" name="price[]" required>
        </label>
        <label>
          <span>${__('GST Rate %', 'surajx-gii-theme')}</span>
          <input type="number" step="0.1" min="0" name="gst_rate[]" required>
        </label>`;
      linesContainer.appendChild(group);
    };

    document.getElementById('gii-add-line').addEventListener('click', (event) => {
      event.preventDefault();
      renderLine();
    });

    renderLine();

    invoiceForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      resultEl.innerHTML = `<p>${GIIThemeConfig.strings.loading}</p>`;
      const formData = new FormData(invoiceForm);

      const payload = {
        customer_name: formData.get('customer_name'),
        gst_number: formData.get('gst_number'),
        line_items: formData.getAll('product_name[]').map((name, index) => ({
          name,
          price: formData.getAll('price[]')[index],
          gst_rate: formData.getAll('gst_rate[]')[index],
        })),
      };

      try {
        const response = await apiFetch({
          path: '/gii-saas/v1/invoices',
          method: 'POST',
          data: payload,
        });
        resultEl.innerHTML = `
          <div class="card">
            <h3>${__('Invoice Generated', 'surajx-gii-theme')}</h3>
            <p>${__('Invoice Number', 'surajx-gii-theme')}: ${response.invoice_number}</p>
            <a class="btn btn-primary" href="${response.pdf_url}" target="_blank" rel="noopener noreferrer">${__('Download PDF', 'surajx-gii-theme')}</a>
          </div>`;
        invoiceForm.reset();
        linesContainer.innerHTML = '';
        renderLine();
      } catch (error) {
        console.error('Invoice create error', error);
        resultEl.innerHTML = `<p>${__('Unable to generate invoice. Please try again.', 'surajx-gii-theme')}</p>`;
      }
    });
  }
})();

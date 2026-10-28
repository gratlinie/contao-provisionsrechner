(() => {
    const format = (template, ...values) => values.reduce((text, value) => text.replace('%s', value), template);

    const init = (root) => {
        const form = root.querySelector('form');
        const messages = JSON.parse(root.dataset.messages || '{}');
        const years = Number(root.dataset.years);
        const euro = new Intl.NumberFormat(root.dataset.locale || 'de-DE', { style: 'currency', currency: root.dataset.currency || 'EUR', maximumFractionDigits: 0 });
        const out = (name, text) => {
            root.querySelector(`[data-out="${name}"]`).textContent = text;
        };

        const update = () => {
            const orders = Number(form.orders.value);
            const value = Number(form.average.value);
            const rate = Number(form.rate.value);
            const offer = form.querySelector('[name="offer"]:checked') || form.querySelector('[name="offer"]');
            const yearly = Number(offer.dataset.yearly);
            const once = Number(offer.dataset.once);

            const commission = orders * 52 * value * rate / 100;
            const ownN = once + years * yearly;
            const perDay = (commission - yearly) / 365;
            const days = perDay > 0 ? Math.ceil(once / perDay) : null;

            out('orders', orders);
            out('average', euro.format(value));
            out('rate', `${rate.toLocaleString(root.dataset.locale || 'de-DE')} %`);
            out('commission', euro.format(commission));
            out('yearly', euro.format(yearly));
            out('once', euro.format(once));
            root.querySelector('[data-row="once"]').style.display = once === 0 ? 'none' : '';
            out('year1', euro.format(commission - yearly - once));
            out('yearN', euro.format(years * commission - ownN));
            out('platformN', euro.format(years * commission));
            out('ownN', euro.format(ownN));

            if (days === null) {
                out('headline', messages.none);
            } else if (once === 0) {
                out('headline', format(messages.monthly, euro.format((commission - yearly) / 12)));
            } else {
                out('headline', format(messages.payback, days > 365 ? messages.moreThanYear : format(days === 1 ? messages.day : messages.days, days)));
            }

            const max = Math.max(years * commission, ownN, 1);
            root.querySelector('[data-bar="platform"]').style.inlineSize = `${years * commission / max * 100}%`;
            root.querySelector('[data-bar="own"]').style.inlineSize = `${ownN / max * 100}%`;
        };

        form.addEventListener('input', update);
        update();
    };

    document.querySelectorAll('[data-provisionsrechner]').forEach(init);
})();

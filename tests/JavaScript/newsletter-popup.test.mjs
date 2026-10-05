import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import test from 'node:test';
import vm from 'node:vm';

const completedKey = 'sanjusto_popup_form_completed_v1';
const lastVisitKey = 'sanjusto_last_visit';
const view = readFileSync(new URL('../../resources/views/home.blade.php', import.meta.url), 'utf8');
const scripts = [...view.matchAll(/<script\b[^>]*>([\s\S]*?)<\/script>/g)];
assert.equal(scripts.length, 1, 'Test the actual, single homepage popup script');
const popupScript = scripts[0][1];

function eventTarget(target = {}) {
    const listeners = new Map();
    target.addEventListener = (type, callback, options = {}) => {
        const entries = listeners.get(type) ?? [];
        entries.push({ callback, once: options.once });
        listeners.set(type, entries);
    };
    target.removeEventListener = (type, callback) => {
        listeners.set(type, (listeners.get(type) ?? []).filter(entry => entry.callback !== callback));
    };
    target.dispatchEvent = event => {
        for (const entry of [...(listeners.get(event.type) ?? [])]) {
            if (entry.once) target.removeEventListener(event.type, entry.callback);
            entry.callback.call(target, { target, ...event });
        }
        return true;
    };
    return target;
}

function element(tag = 'div') {
    const attributes = new Map();
    const classes = new Set(['hidden']);
    return eventTarget({
        tagName: tag.toUpperCase(),
        style: {},
        dataset: {},
        children: [],
        classList: {
            add: name => classes.add(name),
            remove: name => classes.delete(name),
            contains: name => classes.has(name),
        },
        setAttribute(name, value) { attributes.set(name, String(value)); },
        getAttribute(name) { return attributes.get(name) ?? null; },
        hasAttribute(name) { return attributes.has(name); },
        appendChild(child) { this.children.push(child); child.parentNode = this; return child; },
        insertBefore(child) { return this.appendChild(child); },
        querySelector(selector) {
            if (selector.includes('script')) {
                return this.children.find(child => child.tagName === 'SCRIPT') ?? null;
            }
            return null;
        },
    });
}

function browser({
    storage = new Map(),
    cookies = new Map(),
    storageBlocked = false,
    cookiesBlocked = false,
    hostname = 'sanjustoiluminacion.com.ar',
    day = '2026-10-05T12:00:00Z',
} = {}) {
    const modal = element();
    const closeButton = element('button');
    const container = element();
    const elements = new Map([
        ['dailyFormModal', modal],
        ['closeFormModal', closeButton],
        ['formContainer', container],
    ]);
    const cookieWrites = [];
    const document = eventTarget({
        readyState: 'loading',
        head: element('head'),
        body: element('body'),
        getElementById: id => elements.get(id) ?? null,
        createElement: tag => element(tag),
        getElementsByTagName: () => [{ parentNode: element('head') }],
        querySelector: selector => elements.get(selector.replace(/^#/, '')) ?? null,
    });
    Object.defineProperty(document, 'cookie', {
        get() {
            if (cookiesBlocked) throw new Error('Cookies unavailable');
            return [...cookies].map(([name, value]) => `${name}=${value}`).join('; ');
        },
        set(value) {
            if (cookiesBlocked) throw new Error('Cookies unavailable');
            cookieWrites.push(value);
            const [name, ...parts] = value.split(';')[0].split('=');
            cookies.set(name.trim(), parts.join('='));
        },
    });
    const localStorage = {
        getItem(key) {
            if (storageBlocked) throw new Error('Storage unavailable');
            return storage.get(key) ?? null;
        },
        setItem(key, value) {
            if (storageBlocked) throw new Error('Storage unavailable');
            storage.set(key, String(value));
        },
        removeItem(key) {
            if (storageBlocked) throw new Error('Storage unavailable');
            storage.delete(key);
        },
    };
    const now = new Date(day).getTime();
    class ClockDate extends Date {
        constructor(...args) { super(...(args.length ? args : [now])); }
        static now() { return now; }
    }
    const timers = new Map();
    let nextTimer = 0;
    const setTimeout = callback => { timers.set(++nextTimer, callback); return nextTimer; };
    const clearTimeout = id => timers.delete(id);
    const location = { hostname, protocol: 'https:', origin: `https://${hostname}` };
    const window = eventTarget({ document, localStorage, location, setTimeout, clearTimeout });
    const context = vm.createContext({
        window, document, localStorage, location, setTimeout, clearTimeout,
        Date: ClockDate, console,
    });
    vm.runInContext(popupScript, context, { filename: 'home.blade.php:popup' });
    document.readyState = 'interactive';
    document.dispatchEvent({ type: 'DOMContentLoaded' });

    return {
        modal, closeButton, container, storage, cookies, cookieWrites, localStorage,
        isVisible: () => modal.style.display === 'flex',
        emit(type, detail) { window.dispatchEvent({ type, detail }); },
        storageEvent() {
            window.dispatchEvent({ type: 'storage', key: completedKey, newValue: 'true', storageArea: localStorage });
        },
        flushTimers() {
            const pending = [...timers.values()];
            timers.clear();
            pending.forEach(callback => callback());
        },
    };
}

function successfulSubmission(page, identification = { id: '8', sec: 'akv4xt' }) {
    page.emit('b24:form:send:success', { object: { identification }, data: { resultId: 123 } });
}

test('successful Bitrix submission hides the popup and survives a new visit on another day', () => {
    const page = browser();
    page.flushTimers();
    assert.equal(page.isVisible(), true);
    successfulSubmission(page);
    assert.equal(page.isVisible(), false);
    assert.equal(page.storage.get(completedKey), 'true');
    assert.equal(page.cookies.get(completedKey), 'true');

    const revisit = browser({ storage: page.storage, cookies: page.cookies, day: '2026-10-07T12:00:00Z' });
    revisit.flushTimers();
    assert.equal(revisit.isVisible(), false);
    assert.equal(revisit.container.children.length, 0);
});

test('submit, errors, other forms and malformed success events never mark completion', () => {
    const page = browser();
    const matchingForm = { object: { identification: { id: '8', sec: 'akv4xt' } } };
    page.emit('b24:form:submit', matchingForm);
    page.emit('b24:form:send:error', matchingForm);
    page.emit('b24:form:send:success');
    page.emit('b24:form:send:success', {});
    page.emit('b24:form:send:success', { object: {} });
    successfulSubmission(page, { id: '9', sec: 'akv4xt' });
    successfulSubmission(page, { id: '8', sec: 'another-form' });
    assert.equal(page.storage.has(completedKey), false);
    assert.equal(page.cookies.has(completedKey), false);
    page.flushTimers();
    assert.equal(page.isVisible(), true);
});

test('another form initializing first does not consume the target success listener', () => {
    const page = browser();
    page.emit('b24:form:init', { object: { identification: { id: '9', sec: 'other' } } });
    successfulSubmission(page, { id: 8, sec: 'akv4xt' });
    page.flushTimers();
    assert.equal(page.storage.get(completedKey), 'true');
    assert.equal(page.isVisible(), false);
});

test('legacy completion storage is respected and migrated to the domain cookie', () => {
    const page = browser({ storage: new Map([[completedKey, 'true']]), hostname: 'www.sanjustoiluminacion.com.ar' });
    page.flushTimers();
    assert.equal(page.isVisible(), false);
    assert.equal(page.cookies.get(completedKey), 'true');
    const cookie = page.cookieWrites.find(value => value.startsWith(`${completedKey}=`));
    assert.match(cookie, /Domain=\.?sanjustoiluminacion\.com\.ar/i);
    assert.match(cookie, /Max-Age=31536000/i);
    assert.match(cookie, /Path=\//i);
    assert.match(cookie, /SameSite=Lax/i);
    assert.match(cookie, /Secure/i);
});

test('completion cookie hides the popup when localStorage cannot be read', () => {
    const page = browser({ cookies: new Map([[completedKey, 'true']]), storageBlocked: true });
    page.flushTimers();
    assert.equal(page.isVisible(), false);
    assert.equal(page.container.children.length, 0);
});

test('a successful submission persists in the cookie when localStorage is blocked', () => {
    const page = browser({ storageBlocked: true });
    page.flushTimers();
    assert.equal(page.isVisible(), true);
    successfulSubmission(page);
    assert.equal(page.cookies.get(completedKey), 'true');
    assert.equal(page.isVisible(), false);
    const revisit = browser({ cookies: page.cookies, storageBlocked: true, day: '2026-10-07T12:00:00Z' });
    revisit.flushTimers();
    assert.equal(revisit.isVisible(), false);
});

test('closing the popup only suppresses the daily visit, never marks the form submitted', () => {
    const page = browser();
    page.flushTimers();
    page.closeButton.dispatchEvent({ type: 'click' });
    assert.equal(page.isVisible(), false);
    assert.equal(page.storage.has(completedKey), false);
    assert.equal(page.cookies.has(completedKey), false);
    assert.equal(page.storage.has(lastVisitKey), true);
    const sameDay = browser({ storage: page.storage, cookies: page.cookies });
    sameDay.flushTimers();
    assert.equal(sameDay.isVisible(), false);
    const nextDay = browser({ storage: page.storage, cookies: page.cookies, day: '2026-10-06T12:00:00Z' });
    nextDay.flushTimers();
    assert.equal(nextDay.isVisible(), true);
});

test('completion before the delayed open prevents loading the form at all', () => {
    const page = browser();
    successfulSubmission(page);
    page.flushTimers();
    assert.equal(page.isVisible(), false);
    assert.equal(page.container.children.length, 0);
});

test('completion in another tab closes an open popup', () => {
    const page = browser();
    page.flushTimers();
    page.storage.set(completedKey, 'true');
    page.storageEvent();
    assert.equal(page.isVisible(), false);
});

test('pageshow rechecks completion after returning from another page', () => {
    const page = browser();
    page.flushTimers();
    page.cookies.set(completedKey, 'true');
    page.emit('pageshow');
    assert.equal(page.isVisible(), false);
});

test('Bitrix loader is appended once with the form identity inside its container', () => {
    const page = browser();
    page.flushTimers();
    page.emit('pageshow');
    page.flushTimers();
    assert.equal(page.container.children.length, 1);
    const loader = page.container.children[0];
    assert.equal(loader.tagName, 'SCRIPT');
    assert.equal(loader.getAttribute('data-b24-form'), 'inline/8/akv4xt');
    assert.match(loader.src, /^https:\/\/cdn\.bitrix24\.es\/b7493823\/crm\/form\/loader_8\.js(?:\?|$)/);
    assert.equal(loader.async, true);
});

test('blocked storage and cookies still allow success to dismiss the current popup', () => {
    const page = browser({ storageBlocked: true, cookiesBlocked: true });
    successfulSubmission(page);
    page.flushTimers();
    page.emit('pageshow');
    assert.equal(page.isVisible(), false);
});

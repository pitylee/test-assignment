import {Modal} from 'flowbite';

class MyModal {
    constructor(id = 'modalId', options = {}) {
        this._id = id;
        this.debug = false;
        this.instance(id, options);
    }

    instance(id = 'modalId', options = {}) {
        this._instance = new Modal(document.getElementById(id), {
            placement: 'center',
            backdrop: 'dynamic',
            backdropClasses:
                'bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-40',
            closable: true,
            onShow: () => this.onShow(),
            onHide: () => this.onHide(),
            onToggle: () => this.onToggle(),
            ...options
        }, {
            id: 'modalEl',
            override: true
        });

        return this;
    }

    getId() {
        return this._id;
    }

    handle(id) {
        if (!id) {
            return;
        }

        let current = this.instance(id);
        const handleButton = (selector, handle) => {
            const toggleButtons = Array.from(document.querySelectorAll(`[${selector}]`));
            toggleButtons.forEach(button => {
                const currentId = button.getAttribute(selector);
                if (currentId && currentId === id) {
                    button.removeEventListener('click', handle);
                    button.addEventListener('click', handle);
                }
            });
        };
        const handleModalShow = () => {
            current.show();
        };
        const handleModalHide = () => {
            current.hide();
        };
        handleButton('data-modal-toggle', () => {
            current.toggle();
        });
        handleButton('data-modal-show', handleModalShow);
        handleButton('data-modal-open', handleModalShow);
        handleButton('data-modal-hide', handleModalHide);
        handleButton('data-modal-close', handleModalHide);
    }

    show() {
        return this._instance.show();
    }

    hide() {
        setTimeout(() => {
            const backdrop = document.querySelector(' div[modal-backdrop]');
            if (backdrop) {
                backdrop.remove();
            }
        }, 1000);

        return this._instance.hide();
    }

    toggle() {
        return this._instance.toggle();
    }

    isVisible() {
        return this._instance.isVisible();
    }

    isHidden() {
        return this._instance.isHidden();
    }


    onShow() {
        if (this.debug === true) {
            console.log('modal is shown');
        }
    }

    onHide() {
        if (this.debug === true) {
            console.log('modal has been hidden');
        }
    }

    onToggle() {
        if (this.debug === true) {
            console.log('modal has been toggled');
        }
    }
}

const modal = new MyModal();
modal.debug = (['development', 'dev']).includes(process.env.NODE_ENV);

export {modal};
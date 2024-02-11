import {Modal} from 'flowbite';

class MyModal {
    constructor(id = 'modalId', options = {}) {
        this._id = id;
        this._modals = {}; // Map to store modal instances
        this.debug = false;
        this.instance(id, options);
    }

    /*
    * Create instances with general options and put aside to use later on.
    * */
    instance(id = 'modalId', options = {}) {
        if (!this._modals[id]) {
            this._modals[id] = new Modal(document.getElementById(id), {
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
                id: id,
                override: true
            });
        }

        return this._modals[id];
    }

    getId() {
        return this._id;
    }

    /*
    * For when [data] attributes of Flowbite Modal will fail on dynamic elements, we fallback to javascript event listeners
    * */
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

    current() {
        return this.instance(this.getId());
    }

    show() {
        return this.current().show();
    }

    hide() {
        // setTimeout(() => {
        //     const backdrop = document.querySelector(' div[modal-backdrop]');
        //     if (backdrop) {
        //         backdrop.remove();
        //     }
        // }, 3000);

        return this.current().hide();
    }

    toggle() {
        return this.current().toggle();
    }

    isVisible() {
        return this.current().isVisible();
    }

    isHidden() {
        return this.current().isHidden();
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
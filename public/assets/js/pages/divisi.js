(function () {
    const formDivisi = document.querySelector('#formDivisi');
    // Form validation for Add new record
    if (formDivisi) {
        const fv = FormValidation.formValidation(formDivisi, {
            fields: {
                kode_div: {
                    validators: {
                        notEmpty: {
                            message: 'Kode Divisi Harus Disii !'
                        },

                        stringLength: {
                            max: 3,
                            message: 'Kode Divisi Max. 3 Karakter'
                        },
                    }
                },

                nama_div: {
                    validators: {
                        notEmpty: {
                            message: 'Nama Divisi Harus Diisi !'
                        },
                    },

                },
            },
            plugins: {
                trigger: new FormValidation.plugins.Trigger(),
                bootstrap5: new FormValidation.plugins.Bootstrap5({
                    eleValidClass: '',
                    rowSelector: '.mb-3'
                }),
                submitButton: new FormValidation.plugins.SubmitButton(),

                defaultSubmit: new FormValidation.plugins.DefaultSubmit(),
                autoFocus: new FormValidation.plugins.AutoFocus()
            },
            init: instance => {
                instance.on('plugins.message.placed', function (e) {
                    if (e.element.parentElement.classList.contains('input-group')) {
                        e.element.parentElement.insertAdjacentElement('afterend', e.messageElement);
                    }
                });
            }
        });
    }
})();

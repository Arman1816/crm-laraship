{!! \Payments::loadGatewayScripts() !!}

<script type="text/javascript">
    $(document).ready(function () {
        checkoutFormData = $('#checkoutForm').serialize();
        last_success_step = "";
        // Toolbar extra buttons

        // Smart Wizard
        $('#checkoutWizard').smartWizard({
            selected: 0,
            ajaxSettings: {'type': 'GET'},
            theme: 'arrows',
            useURLhash: false,
            keyNavigation: true,
            contentCache: false,
            transitionEffect: 'fade',
            toolbarSettings: {
                toolbarPosition: 'bottom'
            },
            lang: {
                next: '{{ trans('Corals::labels.next') }}',
                previous: '{{ trans('Corals::labels.previous') }}'
            },
        });

        $("#checkoutWizard").on('showStep', function (e, anchorObject, stepNumber, stepDirection) {
            window.scrollTo(0, 0);
        });

        $("#checkoutWizard").on("leaveStep", function (e, anchorObject, stepNumber, stepDirection) {
            current_step = window.location.hash;
            if (stepDirection == "forward") {
                if (last_success_step != current_step) {
                    $step_form = $("#checkoutWizard #checkoutSteps .checkoutStep").eq(stepNumber).find("form:not(:hidden)").last();
                    if ($step_form.length > 0) {
                        $step_form.trigger('submit');
                        return false;
                    }
                }
            } else {
                last_success_step = "";
            }

        });
    });

    function nextCheckoutStep(data) {
        last_success_step = data.lastSuccessStep;
        $("#checkoutWizard").smartWizard('next');
    }

    function checkoutAuth(respnse, $form, hashed_id) {
        $('#checkoutMethod').val('auth');
        $("#checkoutWizard").smartWizard('next');
    }
</script>

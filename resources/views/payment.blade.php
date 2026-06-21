@php
    $mf = number_format($dto->amount, 3, ',', ' ') . ' ' . $dto->currency;
    $formColor = $dto->formColor;
@endphp
<style>
    {!! file_get_contents(app('gopay-ui-path') . '/resources/files/gopay.css') !!}
</style>

<div id="gopay-wrapper">
    <div id="gopay-loader">
        <div class="gopay-spinner"></div>
        <div class="gopay-text">Préparation du paiement sécurisé...</div>
    </div>

    <div class="w-100" id="gopay-div" style="display:none;">
        <div class="card bg-white rounded shadow-md m-2">
            <div class="card-header bg-dark text-white text-center bg-color">
                <h4>
                    <div class="d-inline-flex align-items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 -960 960 960" width="30"
                            fill="#fff">
                            <path
                                d="M880-733.33v506.66q0 27-19.83 46.84Q840.33-160 813.33-160H146.67q-27 0-46.84-19.83Q80-199.67 80-226.67v-506.66q0-27 19.83-46.84Q119.67-800 146.67-800h666.66q27 0 46.84 19.83Q880-760.33 880-733.33ZM146.67-634h666.66v-99.33H146.67V-634Zm0 139.33v268h666.66v-268H146.67Zm0 268v-506.66 506.66Z" />
                        </svg>
                        <span>
                            Paiement de <b>{{ $mf }}</b>
                        </span>
                    </div>
                </h4>
            </div>
            <div class="card-body pb-0">
                <div class="mb-3">
                    <div class="text-center">
                        <div class="d-inline-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                width="20px" fill="#00b74a">
                                <path
                                    d="M226.67-80q-27.5 0-47.09-19.58Q160-119.17 160-146.67v-422.66q0-27.5 19.58-47.09Q199.17-636 226.67-636h60v-90.67q0-80.23 56.57-136.78T480.07-920q80.26 0 136.76 56.55 56.5 56.55 56.5 136.78V-636h60q27.5 0 47.09 19.58Q800-596.83 800-569.33v422.66q0 27.5-19.58 47.09Q760.83-80 733.33-80H226.67Zm0-66.67h506.66v-422.66H226.67v422.66Zm308.5-155.85Q558-325.04 558-356.67q0-31-22.95-55.16Q512.11-436 479.89-436t-55.06 24.17Q402-387.67 402-356.33q0 31.33 22.95 53.83 22.94 22.5 55.16 22.5t55.06-22.52ZM353.33-636h253.34v-90.67q0-52.77-36.92-89.72-36.93-36.94-89.67-36.94-52.75 0-89.75 36.94-37 36.95-37 89.72V-636ZM226.67-146.67v-422.66 422.66Z" />
                            </svg>
                            <small class="p-0 font-weight-bold">
                                Nous utilisons les transactions sécurisées et acceptons les paiements
                                par :
                            </small>
                        </div>
                        @php
                            $basePath = app('gopay-ui-path') . '/resources/files/payment-method/';
                        @endphp
                        <div class="d-flex justify-content-center">
                            <a class="m-1">
                                <img class="img-thumbnail"
                                    src="data:image/png;base64,{{ base64_encode(file_get_contents($basePath . 'airtel.png')) }}"
                                    width="100" height="50">
                            </a>
                            <a class="m-1">
                                <img class="img-thumbnail"
                                    src="data:image/png;base64,{{ base64_encode(file_get_contents($basePath . 'vodacom.png')) }}"
                                    width="100" height="50">
                            </a>
                            <a class="m-1">
                                <img class="img-thumbnail"
                                    src="data:image/png;base64,{{ base64_encode(file_get_contents($basePath . 'orange.png')) }}"
                                    width="100" height="50">
                            </a>
                            <a class="m-1">
                                <img class="img-thumbnail"
                                    src="data:image/png;base64,{{ base64_encode(file_get_contents($basePath . 'afrimoney.png')) }}"
                                    width="100" height="50">
                            </a>
                        </div>
                    </div>
                </div>
                <form id="gopay-form" action="#">
                    <input type="hidden" name="reference" value="{{ $reference }}">
                    <input type="hidden" name="signature" value="{{ $signature }}">
                    <hr>
                    <h3 class="mb-3">Montant à payer : <b>{{ $mf }}</b> </h3>
                    <div class="form-outline mb-3 input-group eflex-nowrap">
                        <span class="input-group-text" id="addon-wrapping">+243</span>
                        <input required id="gopay-phone" class="form-control" name="phone"
                            value="{{ $dto->phone }}" />
                        <label class="form-label" for="gopay-phone">Numéro mobile money</label>
                    </div>

                    <p class="text-muted small mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px"
                            fill="#000">
                            <path
                                d="M448.67-280h66.66v-240h-66.66v240Zm56.5-325.97q10.16-9.96 10.16-24.7 0-15.3-10.15-25.65-10.16-10.35-25.17-10.35-15.01 0-25.18 10.35-10.16 10.35-10.16 25.65 0 14.74 10.15 24.7 10.16 9.97 25.17 9.97 15.01 0 25.18-9.97ZM480.18-80q-82.83 0-155.67-31.5-72.84-31.5-127.18-85.83Q143-251.67 111.5-324.56T80-480.33q0-82.88 31.5-155.78Q143-709 197.33-763q54.34-54 127.23-85.5T480.33-880q82.88 0 155.78 31.5Q709-817 763-763t85.5 127Q880-563 880-480.18q0 82.83-31.5 155.67Q817-251.67 763-197.46q-54 54.21-127 85.84Q563-80 480.18-80Zm.15-66.67q139 0 236-97.33t97-236.33q0-139-96.87-236-96.88-97-236.46-97-138.67 0-236 96.87-97.33 96.88-97.33 236.46 0 138.67 97.33 236 97.33 97.33 236.33 97.33ZM480-480Z" />
                        </svg>
                        <span>
                            Les frais de transaction
                            seront appliqués par votre opérateur mobile
                        </span>
                    </p>

                    @if (config('gopay.environment') === 'sandbox')
                        <div class="alert alert-warning text-center mb-3">
                            <strong>
                                <div class="d-inline-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960"
                                        width="40px" fill="#000">
                                        <path
                                            d="m320-241.33-240-240 241.33-241.34L369-675 175-481l192.33 192.33L320-241.33ZM638.67-240 591-287.67l194-194L592.67-674 640-721.33l240 240L638.67-240Z" />
                                    </svg>
                                    <spanl class="ml-2">MODE TEST (SANDBOX)</spanl>
                                </div>
                            </strong>
                            <div class="mt-2">
                                Aucun paiement réel ne sera effectué.
                            </div>
                            <div class="small mt-2">
                                Pour activer les paiements réels, définissez
                                <code>GOPAY_ENV=production</code>
                                dans votre fichier <code>.env</code>.
                            </div>
                        </div>
                        <div class=" mb-3">
                            <label class="form-label">
                                Résultat du paiement simulé
                            </label>
                            <select class="form-control form-control-sm" name="test_status">
                                <option value="success">Paiement réussi</option>
                                <option value="failed">Paiement échoué</option>
                            </select>
                        </div>
                    @endif
                    <div id="rep" style="display:none"></div>
                    <button type="submit" class="btn w-100 btn-dark bg-color"></button>
                    <button type="button" class="btn w-100 mt-2 btn-secondary" id="btncancel" style="display: none">
                        <div class="d-inline-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960"
                                width="20px" fill="#f93154">
                                <path
                                    d="m332-285.33 148-148 148 148L674.67-332l-148-148 148-148L628-674.67l-148 148-148-148L285.33-628l148 148-148 148L332-285.33ZM480-80q-82.33 0-155.33-31.5-73-31.5-127.34-85.83Q143-251.67 111.5-324.67T80-480q0-83 31.5-156t85.83-127q54.34-54 127.34-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82.33-31.5 155.33-31.5 73-85.5 127.34Q709-143 636-111.5T480-80Zm0-66.67q139.33 0 236.33-97.33t97-236q0-139.33-97-236.33t-236.33-97q-138.67 0-236 97-97.33 97-97.33 236.33 0 138.67 97.33 236 97.33 97.33 236 97.33ZM480-480Z" />
                            </svg>
                            <span class='ml-2'>Annuler</span>
                        </div>
                    </button>

                    <div class="text-right mt-5">
                        <small class="text-muted">
                            © {{ date('Y') }}
                            <a href="https://gopay.gooomart.com?source=ui" style="color:#262626"
                                target="_blank">GoPAY</a>
                            · Powered by
                            <a href="https://gooomart.com?source=ui" class="text-warning"
                                target="_blank">Gooomart</a>
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    #gopay-wrapper {
        position: relative;
        min-height: 250px;
    }

    #gopay-loader {
        position: absolute;
        inset: 0;
        background: rgba(255, 255, 255, 0.92);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        backdrop-filter: blur(4px);
    }

    .gopay-spinner {
        width: 45px;
        height: 45px;
        border: 4px solid #eee;
        border-top: 4px solid {{ $formColor }};
        border-radius: 50%;
        animation: spin 0.5s linear infinite;
        margin-bottom: 12px;
    }

    .gopay-text {
        font-size: 14px;
        color: #333;
        font-weight: 500;
        letter-spacing: 0.2px;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
</style>
<script>
    {!! file_get_contents(app('gopay-ui-path') . '/resources/files/jq.min.js') !!}
</script>
<script>
    {!! file_get_contents(app('gopay-ui-path') . '/resources/files/jquery.mask.min.js') !!}
</script>
<script>
    {!! file_get_contents(app('gopay-ui-path') . '/resources/files/gopay.js') !!}
</script>
<script>
    var gopayform = $('#gopay-form');
    var pinput = $('#gopay-phone', gopayform);
    var paybtnlabel = '{{ $dto->payBtnLabel }}'
    const icon = {
        idle: `<svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="#fff"> <path d="M226.67-80q-27.5 0-47.09-19.58Q160-119.17 160-146.67v-422.66q0-27.5 19.58-47.09Q199.17-636 226.67-636h60v-90.67q0-80.23 56.57-136.78T480.07-920q80.26 0 136.76 56.55 56.5 56.55 56.5 136.78V-636h60q27.5 0 47.09 19.58Q800-596.83 800-569.33v422.66q0 27.5-19.58 47.09Q760.83-80 733.33-80H226.67Zm0-66.67h506.66v-422.66H226.67v422.66Zm308.5-155.85Q558-325.04 558-356.67q0-31-22.95-55.16Q512.11-436 479.89-436t-55.06 24.17Q402-387.67 402-356.33q0 31.33 22.95 53.83 22.94 22.5 55.16 22.5t55.06-22.52ZM353.33-636h253.34v-90.67q0-52.77-36.92-89.72-36.93-36.94-89.67-36.94-52.75 0-89.75 36.94-37 36.95-37 89.72V-636ZM226.67-146.67v-422.66 422.66Z"/></svg>`,
        loading: `<svg width="18" height="18" viewBox="0 0 50 50" fill="none"><circle cx="25" cy="25" r="20" stroke="white" stroke-width="5" stroke-linecap="round" stroke-dasharray="31.4 31.4"> <animateTransform attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.8s" repeatCount="indefinite" /></circle></svg>`,
        check: `<svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#00b74a"><path d="M422-297.33 704.67-580l-49.34-48.67L422-395.33l-118-118-48.67 48.66L422-297.33ZM480-80q-82.33 0-155.33-31.5-73-31.5-127.34-85.83Q143-251.67 111.5-324.67T80-480q0-83 31.5-156t85.83-127q54.34-54 127.34-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82.33-31.5 155.33-31.5 73-85.5 127.34Q709-143 636-111.5T480-80Zm0-66.67q139.33 0 236.33-97.33t97-236q0-139.33-97-236.33t-236.33-97q-138.67 0-236 97-97.33 97-97.33 236.33 0 138.67 97.33 236 97.33 97.33 236 97.33ZM480-480Z"/></svg>`,
        close: `<svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#f93154"><path d="m332-285.33 148-148 148 148L674.67-332l-148-148 148-148L628-674.67l-148 148-148-148L285.33-628l148 148-148 148L332-285.33ZM480-80q-82.33 0-155.33-31.5-73-31.5-127.34-85.83Q143-251.67 111.5-324.67T80-480q0-83 31.5-156t85.83-127q54.34-54 127.34-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 82.33-31.5 155.33-31.5 73-85.5 127.34Q709-143 636-111.5T480-80Zm0-66.67q139.33 0 236.33-97.33t97-236q0-139.33-97-236.33t-236.33-97q-138.67 0-236 97-97.33 97-97.33 236.33 0 138.67 97.33 236 97.33 97.33 236 97.33ZM480-480Z"/></svg>`
    };

    $(':submit', gopayform).html(
        `<div class='d-inline-flex align-items-center'>${icon.idle} <span class='ml-2'>${paybtnlabel}</span></div>`
    );

    pinput.mask('000000000');

    REF = '';
    var cancheck = false;

    var callback = function() {
        if (!cancheck) return;
        $.ajax({
            url: '{{ route('gopay.check') }}',
            data: {
                myref: REF
            },
            success: function(res) {
                var trans = res.transaction;
                var status = trans?.status;
                // 'success|failed|pending'
                if (status === 'success') {
                    cancheck = false;
                    // PAYMENT WAS SUCCESS !!
                    var btn = $(':submit', gopayform).attr('disabled', false);
                    btn.html(
                        `<div class='d-inline-flex align-items-center gap-2'>${icon.idle} <span class='ml-2'>${paybtnlabel}</span></div>`
                    );
                    btn.removeClass('btn-danger').addClass('btn-dark');
                    rep = $('#rep', gopayform);
                    rep.html(res.message).removeClass();
                    rep.addClass('alert alert-success');
                    rep.slideDown();
                    gopayform.html(`<div class="my-5 text-center">
                            <p class="text-success">
                                <div class='d-inline-flex align-items-center gap-2 font-weight-bold text-success'>${icon.check} <span class='h3'> VOTRE TRANSANCTION A R&Eacute;USSIE !</span></div>
                            </p>
                            <p id="ltimer"></p>
                        </div>`);
                    let seconds = 5;
                    var ltimer = $('#ltimer');
                    const timer = setInterval(() => {
                        ltimer.html(
                            `Redirection dans ${seconds} seconde(s)...`);
                        seconds--;
                        if (seconds < 0) {
                            clearInterval(timer);
                            const action = res.action || {};
                            switch (action.onSuccess) {
                                case 'refresh_page':
                                    window.location.reload();
                                    break;
                                case 'go_to_url':
                                    if (action.redirectUrl) {
                                        window.location.href = action.redirectUrl;
                                    }
                                    break;
                                default:
                                    console.warn('Unknown onSuccess action:', action
                                        .onSuccess);
                            }
                        }
                    }, 1000);

                } else if (status === 'failed') {
                    cancheck = false;
                    $('#btncancel').hide();
                    $('#btnclose').show();
                    var btn = $(':submit', gopayform).attr('disabled', false);
                    btn.html(
                        `<div class='d-inline-flex align-items-center gap-2'>${icon.idle} <span class='ml-2'>${paybtnlabel}</span></div>`
                    );
                    var rep = $('#rep', gopayform);
                    var html = `<div class="my-2 text-center">
                            <p>
                                <div class='d-inline-flex align-items-center gap-2 font-weight-bold text-danger'>${icon.close} <span class='h3 ml-2'> VOTRE PAIEMENT A &Eacute;CHOU&Eacute; !</span></div>
                            </p>
                            <p>Vous avez peut-être saisie un mauvais pin ou votre solde est insuffisant.</p>
                        </div>`;
                    rep.html(html).removeClass().addClass('alert alert-danger');
                }
            }
        }).always(function() {
            if (cancheck) {
                setTimeout(() => {
                    callback();
                }, 3000);
            }
        });
    }

    $('#btncancel').click(function() {
        cancheck = false;
        $(this).hide();
        var btn = $(':submit', gopayform).attr('disabled', false);
        btn.html(
            `<div class='d-inline-flex align-items-center gap-2'>${icon.idle} <span class='ml-2'>${paybtnlabel}</span></div>`
        );
        btn.removeClass('btn-dark').addClass('btn-dark');
        var rep = $('#rep', gopayform);
        rep.html("Paiement annulé.").removeClass();
        rep.addClass('alert alert-warning');
    });
    gopayform.submit(function() {
        event.preventDefault();
        rep = $('#rep', gopayform);
        rep.stop().slideUp();
        if (pinput.val().toString().length != 9) {
            rep.html("Numéro de téléphone non valide");
            rep.removeClass();
            rep.addClass('alert alert-danger');
            rep.stop();
            rep.slideDown();
            return;
        }

        var btn = $(':submit', gopayform).attr('disabled', true);
        btn.html(
            `<div class='d-inline-flex align-items-center gap-2'>${icon.loading} <span class='ml-2'>${paybtnlabel}</span></div>`
        );
        var data = gopayform.serialize();
        $.ajax({
            url: '{{ route('gopay.init') }}',
            type: 'POST',
            data: data,
            timeout: 30000,
            success: function(res) {
                if (res.success == true) {
                    rep.html(res.message).removeClass();
                    rep.addClass('alert alert-success');
                    rep.slideDown();
                    btn.html(
                        `<div class='d-inline-flex align-items-center'>${icon.loading} <span class='ml-2'>En attente de validation</span></div>`
                    );;
                    btn.attr('disabled', true).removeClass('btn-dark').addClass(
                        'btn-danger');
                    REF = res.data.myref;
                    $('#btncancel').show();

                    cancheck = true;
                    @if (config('gopay.environment') === 'sandbox')
                        setTimeout(() => {
                            callback();
                        }, 3000);
                    @else
                        callback();
                    @endif
                } else {
                    var m = res.message;
                    rep.removeClass().addClass('alert alert-danger').html(m)
                        .slideDown();
                    btn.attr('disabled', false).html(
                        `<div class='d-inline-flex align-items-center gap-2'>${icon.idle} <span class='ml-2'>${paybtnlabel}</span></div>`
                    );
                }
            },
            error: function(resp) {
                var mess = resp.responseJSON?.message ??
                    "Une erreur s'est produite, merci de réessayer";
                rep.removeClass().addClass('alert alert-danger').html(mess)
                    .slideDown();
                btn.attr('disabled', false).html(
                    `<div class='d-inline-flex align-items-center gap-2'>${icon.idle} <span class='ml-2'>${paybtnlabel}</span></div>`
                );
            }
        });
    });

    $(function() {
        const loader = document.getElementById('gopay-loader');
        const content = document.getElementById('gopay-div');
        setTimeout(() => {
            loader.style.opacity = '0';
            loader.style.transition = 'opacity 0.3s ease';
            setTimeout(() => {
                loader.style.display = 'none';
                content.style.display = 'block';
                $('.form-outline', this).each((i, el) => {
                    new mdb.Input(el).update();
                });
            }, 500);
        }, 300);
    })
</script>
<style>
    #gopay-div *>.bg-color {
        background-color: {{ $formColor }} !important;
    }
</style>

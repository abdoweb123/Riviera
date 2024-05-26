
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/intl-tel-input@17.0.3/build/css/intlTelInput.css">
    <style>
        #phone{
            padding-left: 90px;
            padding-right: 90px;
        }
    </style>
@endpush

@push('js')
    <script src="https://unpkg.com/intl-tel-input@17.0.3/build/js/intlTelInput.js"></script>
    <script>
        var iti = window.intlTelInput(document.querySelector("#phone"), {
            separateDialCode: true
            , autoFormat: false
            , utilsScript: "https://unpkg.com/intl-tel-input@17.0.3/build/js/utils.js"
            , preferredCountries: @json(countries()->pluck('country_code'))
        , });
        window.iti = iti;
        iti.setCountry("{{ old('country_code',isset($country_code) ? $country_code : country_code()) }}");
        document.querySelector("#phone").addEventListener("countrychange", function() {
            document.getElementById("phone").value = '';
            document.getElementById("country_code").value = iti.getSelectedCountryData().iso2.toUpperCase();
            document.getElementById("phone_code").value = iti.getSelectedCountryData().dialCode;
        })
    </script>
@endpush
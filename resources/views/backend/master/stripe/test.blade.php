<style>
  .StripeElement {
  box-sizing: border-box;

  height: 40px;

  padding: 10px 12px;

  border: 1px solid transparent;
  border-radius: 4px;
  background-color: white;

  box-shadow: 0 1px 3px 0 #e6ebf1;
  -webkit-transition: box-shadow 150ms ease;
  transition: box-shadow 150ms ease;
  }

  .StripeElement--focus {
  box-shadow: 0 1px 3px 0 #cfd7df;
  }

  .StripeElement--invalid {
  border-color: #fa755a;
  }

  .StripeElement--webkit-autofill {
  background-color: #fefde5 !important;
  }
</style>
<div id="wrapper">
  @include('backend.menus.sidebar_menu',array("login" => $login, "previlege" => $previlege))
  <div id="page-wrapper" class="gray-bg">
    @include('backend.menus.top',array("login" => $login, "previlege" => $previlege))
    <div class="row wrapper border-bottom white-bg page-heading">
            <div class="col-lg-10">
                <h2>Stripe Account</h2>
                <ol class="breadcrumb">
                    <li>
                        <a href="{{ url('dashboard/view') }}">Dashboard</a>
                    </li>
                    <li class="active">
                        <strong>Stripe Account</strong>
                    </li>
                </ol>
            </div>
            <div class="col-lg-2">

            </div>
        </div>
        <div class="wrapper wrapper-content animated fadeInRight">
            <div class="row">
                <div class="col-lg-6">
                  <h3>STRIPE SETTING</h3>
                  @include('backend.flash_message')
                  {!! Form::open(['url' => url('master/stripe-update'), 'method' => 'post', 'id' => 'formmain','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                  <div class="form-group {{ ($errors->has("stripe_publishable_key")?"has-error":"") }}"><label class="col-sm-4 control-label">Stripe Publishable Key</label>
                      <div class="col-sm-8 col-xs-12">
                          {!! Form::text("stripe_publishable_key", $api_key, ['class' => 'form-control']) !!}
                          {!! $errors->first("stripe_publishable_key", '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                      </div>
                  </div>

                  <div class="form-group {{ ($errors->has("stripe_secret_key")?"has-error":"") }}"><label class="col-sm-4 control-label">Stripe Secret Key</label>
                      <div class="col-sm-8 col-xs-12">
                          {!! Form::text("stripe_secret_key", $stripe_secret_key, ['class' => 'form-control']) !!}
                          {!! $errors->first("stripe_secret_key", '<p class="bg-danger p-xs b-r-sm m-t">:message</p>') !!}
                      </div>
                  </div>
                  <div class="form-group {{ ($errors->has("stripe_secret_key")?"has-error":"") }}"><label class="col-sm-4 control-label"></label>
                      <div class="col-sm-8 col-xs-12">
                          <button class="btn btn-primary" type="submit">Save Changes</button>
                      </div>
                  </div>

                  {!! Form::close() !!}
                </div>
              </div>
              <br><br>
              <div class="row">
                <div class="col-lg-6">
                  <h3>STRIPE PAYMENT TEST</h3>
                  <script src="https://js.stripe.com/v3/"></script>
                  <p class='alert alert-info'>You will be able to test your card here. Make sure your card accepted and your payment should be valid.</p>
                    {!! Form::open(['url' => url('master/stripe-charge'), 'method' => 'post', 'id' => 'payment-form','class' => 'form-horizontal', 'data-parsley-validate novalidate']) !!}
                    <div class="form-row">
                      <label for="card-element">
                        Credit or debit card
                      </label>
                      <div id="card-element">
                        <!-- A Stripe Element will be inserted here. -->
                      </div>

                      <!-- Used to display form errors. -->
                      <div id="card-errors" role="alert"></div>
                    </div>
                    <br>
                    <button class="btn btn-primary">Submit Payment</button>
                  {!! Form::close() !!}
                  <script>
                  // Create a Stripe client.
                  var stripe = Stripe("<?=$api_key?>");

                  // Create an instance of Elements.
                  var elements = stripe.elements();

                  // Custom styling can be passed to options when creating an Element.
                  // (Note that this demo uses a wider set of styles than the guide below.)
                  var style = {
                  base: {
                    color: '#32325d',
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: 'antialiased',
                    fontSize: '16px',
                    '::placeholder': {
                      color: '#aab7c4'
                    }
                  },
                  invalid: {
                    color: '#fa755a',
                    iconColor: '#fa755a'
                  }
                  };

                  // Create an instance of the card Element.
                  var card = elements.create('card', {style: style});

                  // Add an instance of the card Element into the `card-element` <div>.
                  card.mount('#card-element');

                  // Handle real-time validation errors from the card Element.
                  card.on('change', function(event) {
                  var displayError = document.getElementById('card-errors');
                  if (event.error) {
                    displayError.textContent = event.error.message;
                  } else {
                    displayError.textContent = '';
                  }
                  });

                  // Handle form submission.
                  var form = document.getElementById('payment-form');
                  form.addEventListener('submit', function(event) {
                  event.preventDefault();

                  stripe.createToken(card).then(function(result) {
                    if (result.error) {
                      // Inform the user if there was an error.
                      var errorElement = document.getElementById('card-errors');
                      errorElement.textContent = result.error.message;
                    } else {
                      // Send the token to your server.
                      stripeTokenHandler(result.token);
                    }
                  });
                  });

                  // Submit the form with the token ID.
                  function stripeTokenHandler(token) {
                    // Insert the token ID into the form so it gets submitted to the server
                    var form = document.getElementById('payment-form');
                    var hiddenInput = document.createElement('input');
                    hiddenInput.setAttribute('type', 'hidden');
                    hiddenInput.setAttribute('name', 'stripeToken');
                    hiddenInput.setAttribute('value', token.id);
                    form.appendChild(hiddenInput);

                    // Submit the form
                    form.submit();
                  }
                  </script>
                </div>
            </div>
        </div>
    @include('backend.do_confirm')
    @include('backend.footer')
  </div>
</div>

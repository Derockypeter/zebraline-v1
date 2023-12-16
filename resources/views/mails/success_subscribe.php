<!DOCTYPE html>
<html>
    <body>
    <h3>{{ $detail['names'] }}</h3>
   
    <h5>Successful Subscription!</h5>
    <strong><b>Before you start sharing your website, please add a payout option</b></strong> 

    <p>Next step -:</p> 
        <p>Add a method of receiving payments either stripe or paystack or flutterwave <a href="{{url('/vendor/payment)}}">(Navigate to Account).</a></p>
        <p>Setup stripe (Recommended for most countries)</p>
            <p>- To use stripe please follow this guide to get you to speed <a href="https://support.stripe.com/topics/getting-started#">Stripe Guide</a>.<p>
        <p>Setup Flutterwave</p>
            <p>- To use flutterwave please follow this guide <a href="https://www.youtube.com/watch?v=-vbOpcQ4TB4">Flutterwave</a></p>
        <p>Setup Paystack</p>
            <p>- To use Paystack please follow this guide <a href="https://support.paystack.com/hc/en-us/articles/360009973659-How-do-I-create-a-Paystack-account-">Paystack</a></p>
        

    <strong>At least add one blog</strong> <a href="{{url('/vendor/blog)}}">(Navigate to Account).</a><br>

    You will receive an email when your chosen domain is live and ready.
    <strong><b>NOTE:</b> - Your chosen domain will be ready in 15 minutes.</strong>

    <p>Reach out to us if you need any assistance!</p>

    <p>Thank You</p>

    <p>Zebraline</p>
</body>
</html>



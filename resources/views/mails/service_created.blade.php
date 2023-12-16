<!DOCTYPE html>
<html>
    <body>
    <h3>Dear Dr {{ $detail['names'] }},</h3>
    <p>
        Congratulations on the successful creation of your new website. It is truly a remarkable achievement towards managing your brand as a physician and in line with our conviction that most of your patients are going to Google you, we feel you should have some control over what they read about you.
    </p>
    <p>
        Firstly, I would like to provide you with the login credentials for your website's control center:<br/>

        - Website URL: https://{{str_replace('.com', '', $detail['domain'])}}.whitecoatdomain.com (<a href="https://{{str_replace('.com', '', $detail['domain'])}}.whitecoatdomain.com" target="_blank">link</a>)<br/>
        - Control center URL: https://{{str_replace('.com', '', $detail['domain'])}}.whitecoatdomain.com/setting (<a href="https://{{str_replace('.com', '', $detail['domain'])}}.whitecoatdomain.com/setting" target="_blank">link</a>)<br/>
        - Username: {{ $detail['email'] }}<br/>
        - Password: {{ $detail['password'] }}
    </p>
    <p>
        Please ensure that you keep this information secure and accessible only to authorized individuals who require access to maintain your website.
    </p>
    <p>
        I would also like to offer some suggestions for further upgrades and enhancements to your site, which can help enhance your users' experience and ensure its continued success:
        <ul>
            <li>
            <b>Mini Blog:</b> You can offer your patients bite-sized content that delivers quick information or insights. You can use it to capture attention, provide value, and encourage further exploration of the topic through concise and visually appealing posts.
            </li>
            <li>
            <b>Search Engine Optimization (SEO):</b> Optimize your website's content and structure to improve its visibility in search engine results. This will help potential visitors find your site more easily and increase organic traffic.
            </li>
            <li>
            <b>Social Media Stream:</b> With this your website visitors can view and engage with your social media posts, updates, or feeds without leaving your website.
            </li>
            <li>
            <b>Contact Information:</b> Provide contact information like branded email eg. consultancy@{{$detail['domain']}}.com, address and phone number.
            </li>
        </ul>
    </p>

    <p>Best regards,</p>

    <p>White Coat Domain</p>
</body>
</html>



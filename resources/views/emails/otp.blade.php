<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kode OTP Pajajap</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f6f8; font-family:Arial, Helvetica, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding:40px 0;">
                <table width="100%" style="max-width:480px; background:#ffffff; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.08);" cellpadding="0" cellspacing="0">
                    
                    <!-- HEADER -->
                    <tr>
                        <td style="padding:24px; text-align:center; border-bottom:1px solid #eee;">
                            <h1 style="margin:0; font-size:22px; color:#111;">
                                Pajajap
                            </h1>
                            
                        </td>
                    </tr>

                    <!-- CONTENT -->
                    <tr>
                        <td style="padding:28px;">
                            

                            <p style="margin:0 0 20px; font-size:14px; color:#555; line-height:1.6;">
                                Gunakan kode OTP di bawah ini untuk melanjutkan proses registrasi dan pendaftaran driver kamu di <b>Pajajap</b>.
                            </p>

                            <!-- OTP BOX -->
                            <div style="text-align:center; margin:24px 0;">
                                <div style="
                                    display:inline-block;
                                    padding:14px 28px;
                                    font-size:28px;
                                    letter-spacing:6px;
                                    font-weight:bold;
                                    background:#f1f3f5;
                                    color:#111;
                                    border-radius:10px;
                                ">
                                    {{ $otp }}
                                </div>
                            </div>

                            <p style="margin:0 0 10px; font-size:13px; color:#777;">
                                Kode ini berlaku selama <b>3 menit</b>.
                            </p>

                            <p style="margin:0; font-size:13px; color:#999;">
                                Jika kamu tidak merasa mendaftar di Pajajap, abaikan email ini.
                            </p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="padding:18px; text-align:center; background:#fafafa; border-top:1px solid #eee; border-radius:0 0 12px 12px;">
                            <p style="margin:0; font-size:12px; color:#aaa;">
                                © {{ date('Y') }} Pajajap. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

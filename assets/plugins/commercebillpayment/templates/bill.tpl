<html>
    <body style="font-size: 13px;">
        <p style="text-align: center;">
            Внимание! Оплата данного счета означает согласие с условиями поставки товара. Уведомление об оплате товара обязательно, в противном случае не гарантируется наличие товара на складе. Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.
        </p>

        <table style="border-collapse: collapse; width: 100%; font-size: 90%;">
            <tr>
                <td style="border: 1px solid #555; padding: 5px;">ИНН [+settings.inn+]</td>
                <td style="border: 1px solid #555; padding: 5px;">КПП [+settings.kpp+]</td>
                <td rowspan="2" style="border: 1px solid #555; padding: 5px; vertical-align: top;">Сч. №</td>
                <td rowspan="2" style="border: 1px solid #555; padding: 5px; vertical-align: top;">[+settings.account+]</td>
            </tr>

            <tr>
                <td colspan="2" style="border: 1px solid #555; padding: 5px;">
                    [+settings.orgname+]<br><br>
                    Получатель
                </td>
            </tr>

            <tr>
                <td colspan="2" style="border: 1px solid #555; border-width: 1px 1px 0; padding: 5px;">[+settings.bank+]</td>
                <td style="border: 1px solid #555; padding: 5px; vertical-align: top;">БИК</td>
                <td style="border: 1px solid #555; padding: 5px; border-width: 1px 1px 0; vertical-align: top;">[+settings.bik+]</td>
            </tr>

            <tr>
                <td colspan="2" style="border: 1px solid #555; padding: 5px; border-width: 0 1px 1px;">Банк получателя</td>
                <td style="border: 1px solid #555; padding: 5px; vertical-align: top;">Сч. №</td>
                <td style="border: 1px solid #555; padding: 5px; border-width: 0 1px 1px; vertical-align: top;">[+settings.bankaccount+]</td>
            </tr>
        </table>

        <h1 style="text-align: center;">
            Счет № [+order.id+] от [+date+]
        </h1>

        <table style="border: 0; margin-bottom: 30px;">
            <tr>
                <td style="padding: 0 50px 40px 0; vertical-align: top;">Поставщик:</td>
                <td style="vertical-align: top;">[+settings.orgname+], [+settings.address+], ИНН&nbsp;[+settings.inn+], ОГРН&nbsp;[+settings.ogrn+]</td>
            </tr>
            <tr>
                <td style="vertical-align: top;">Покупатель:</td>
                <td style="vertical-align: top;">[+order.name+], [+order.phone+]</td>
            </tr>
        </table>

        <table style="border: 2px solid #333; border-collapse: collapse; width: 100%;">
            <tr>
                <td style="border: 1px solid #555; padding: 5px; text-align: center;">№</td>
                <td style="border: 1px solid #555; padding: 5px; text-align: center;">Наименование номенклатуры</td>
                <td style="border: 1px solid #555; padding: 5px; text-align: center;">Количество</td>
                <td style="border: 1px solid #555; padding: 5px; text-align: center;">Ед.</td>
                <td style="border: 1px solid #555; padding: 5px; text-align: center;">Цена</td>
                <td style="border: 1px solid #555; padding: 5px; text-align: center;">Сумма</td>
            </tr>

            [+products+]
        </table>

        <p style="text-align: right; font-weight: bold;">Итого:&nbsp;&nbsp;&nbsp;&nbsp;[+total_fmt+]</p>
        <p style="text-align: right;">([+total_ext+])</p>

        <table style="width: 100%; margin-top: 50px;">
            <tr>
                <td style="padding: 5px; width: 20%;">Руководитель</td>
                <td style="border-bottom: 1px solid #555; padding: 5px; width: 30%;">
                    <div style="width: 1px; height: 1px;">
                        <img src="/assets/images/sign.png" alt="" style="width: 100px; margin: 0 0 -35px 50px;">
                    </div>
                </td>
                <td style="width: 5%;"></td>
                <td style="border-bottom: 1px solid #555; padding: 5px; text-align: center;">[+settings.contact+]</td>
            </tr>

            <tr>
                <td></td>
                <td style="text-align: center; font-size: 50%;">подпись</td>
                <td></td>
                <td style="text-align: center; font-size: 50%;">расшифровка подписи</td>
            </tr>

            <tr>
                <td></td>
                <td style="text-align: right; font-size: 70%;">
                    М.П.
                    <div style="width: 1px; height: 1px;">
                        <img src="/assets/images/stamp.png" alt="" style="width: 130px; margin: -65px -65px 0 0;">
                    </div>
                </td>
                <td></td>
                <td></td>
            </tr>
        </table>

        
    </body>
</html>

import { QrCodePix } from 'qrcode-pix';

async function generatePIX(
    totalOperation: number, 
    pixKey: string,
) {
    const qrCodePix = QrCodePix({
        version: '01',
        key: pixKey, 
        name: 'aaaaa',
        city: 'aaaaaaa',
        transactionId: '123456789',
        message: 'Volte sempre!',
        cep: '00000000',
        value: totalOperation,
    });

    let payload = qrCodePix.payload();
    const base64 = await qrCodePix.base64();
    
    return {
        base64,
        payload,
        
    };
};

export default generatePIX

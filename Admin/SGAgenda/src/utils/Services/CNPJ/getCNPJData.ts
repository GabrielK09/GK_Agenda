import axios from 'axios';

interface AddessData {
    cep: string, // zip
    address: string,
    complement: string,
    neighborhood: string,
    municipality: string,
    uf: string,
    number: string
};

interface CNPJData {
    companyName: string,
    tradeName: string,
    cnpj: string,
    address: AddessData
};  

async function getCNPJData(cnpj: string): Promise<CNPJData|undefined> {
    let cnpjData: CNPJData = {
        companyName: '',
        tradeName: '',
        cnpj: '',
        address: {
            address: '',
            cep: '',
            complement: '',
            municipality: '',
            neighborhood: '',
            number: '',
            uf: ''
        }
    };

    try {
        const res = await axios.get(`${process.env.API_CNPJ}/${cnpj}`);
        const data = res.data;

        return cnpjData = {
            companyName: data.company.name,
            tradeName: cnpjData.companyName,
            cnpj:  data.company.id,
            address: {
                neighborhood: data.address.district,
                complement: data.address.street, 
                address: data.address.street,
                cep: data.address.zip,
                municipality: data.address.city,
                number: data.address.number,
                uf: data.address.state
            }
        };        
    } catch (error) {
        console.error('getCNPJData:', error);
        return undefined;
    };
};

export default getCNPJData;
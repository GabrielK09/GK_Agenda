import axios from 'axios';

interface ViaCepData {
    erro: string,
    cep: string,
    logradouro: string,
    complemento: string,
    bairro: string,
    localidade: string,
    uf: string
};

interface CEPData {
    cep: string,
    address: string,
    complement: string,
    neighborhood: string,
    municipality: string,
    uf: string

};

async function getCEPData(cep: string): Promise<CEPData|string>
{
    let cepData: CEPData = {
        cep: '',
        address: '',
        complement: '',
        neighborhood: '',
        municipality: '',
        uf: ''

    };
    try {
        const res = await axios.get(`${process.env.API_CEP}/${cep}/json`);
        const data: ViaCepData = res.data;

        if(data.erro === 'true')
        {
            return 'CEP não encontrado';
        };

        return cepData = {
            cep: data.cep,
            address: data.logradouro,
            complement: data.complemento,
            neighborhood: data.bairro,
            municipality: data.localidade,
            uf: data.uf

        };
    } catch (error) {
        return 'Erro interno na consulta';
    };
};

export default { getCEPData };
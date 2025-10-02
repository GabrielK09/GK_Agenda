function chromosomeCheck(sperm = '') 
{
    if(sperm.toLocaleLowerCase().includes('x'))
    {
        return "Congratulations! You're going to have a daughter.";
    } 
    
    if (sperm.toLocaleLowerCase().includes('y')) {
        return "Congratulations! You're going to have a son.";
    };
};

chromosomeCheck('XY')
var rows = [['a', 1, '1'], ['b', 2, '2'], ['c', 3, '3'], ['d', 0, '0']]
const high = rows.reduce((m, v) => {
    console.log('v:',v)
    console.log('v1:', v[1])
    console.log('m:',m)
    console.log('res:', v[1] > m ? v[1] : m)
    console.log('=----=')
    return  v[1] > m ? v[1] : m
}, 0)


console.log(high)
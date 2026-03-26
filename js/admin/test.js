function count(){
    var array = [1,2,3,4,5];
    const remove = array.indexOf(3);
    var xoa = array.splice(remove, 1);
    // array = xoa;
    console.log(array);
}

count()
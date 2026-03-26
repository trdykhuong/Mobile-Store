const modal = document.getElementById("modal");
const formAddNCC = document.getElementById("NCCAdd-form");
const formDetailNCC = document.getElementById("NCCDetail-form");

OnloadData = () => {
    LoadProviders('');
}

document.addEventListener('click', (e)=> {
    if (modal.contains(e.target) 
        && !formAddNCC.contains(e.target)
        && !formDetailNCC.contains(e.target)) {
            modal.classList.remove("open-modal");
            CloseDetail();
            formDetailNCC.classList.remove("open-detail");
            formAddNCC.classList.remove("open-add");
            document.querySelector(".hidden-log-out").classList.remove("active");
    }
})

//Filter nhà cung cấp
const order_filter = document.querySelector('select[name=order]')
const text_search = document.querySelector('input[name=search-filter]')

order_filter.addEventListener('change', ()=>{
    const order = order_filter.value

    LoadProviders('', order)
})

text_search.addEventListener('input', ()=>{
    const text = text_search.value

    LoadProviders(text)
})
//Filter nhà cung cấp

//Load danh sách ncc lên
LoadProviders = async(text, order) => {
    const url = '../Controller/Provider/ListProvider.php';
    let result = document.getElementById('result');

    const ListProvider = async() =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    text: text,
                    order: order
                })
            });

            const data = response.json();
            return data;
        } catch (err) {
            console.error(err)  
        }
    }

    const providers = await ListProvider();
    if(providers.length == 0){
        result.innerHTML = 'Không tìm thấy nhà nào á ấy';
        return;
    }

    result.innerHTML = '';
    let rows = document.getElementById('providerRows');
    rows.innerHTML = '';

    providers.forEach(element => {
        rows.innerHTML +=`<tr class='Provider-rows' onclick="OpenDetail(${element.id})">
            <input type='hidden' name='trangthai-rows' value=${element.trangthai}>
            <td>${element.id}</td>
            <td>${element.ten}</td>
            <td>${element.sdt}</td>
            <td>${element.diachi}</td>
            </tr>`;
    });
}

//Cập nhật 
OpenDetail = async(idncc) =>{
    await LoadDataUpdate(idncc);
    modal.classList.add("open-modal");
    formDetailNCC.classList.add("open-detail");
    document.querySelector(".hidden-log-out").classList.add("active");
}

CloseDetail = ()=>{
    modal.classList.remove("open-modal");
    formDetailNCC.classList.remove("open-detail");
    document.querySelector(".hidden-log-out").classList.remove("active");  
}

LoadDataUpdate = async(idNCC) =>{
    const url = '../Controller/Provider/ShowDetail.php?idNCC=' + idNCC;

    const LoadProvider = async()=>{
        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {
                'Content-Type': 'application/json'
                }
            });
    
            const data = response.json();
            return data;
        } catch (error) {
            console.error(error);
        }
    }

    const providerinfo = await LoadProvider();
    document.querySelector('p[name=maNCC]').innerHTML = providerinfo.id;
    document.querySelector('input[name=name-updt]').value = providerinfo.ten;
    document.querySelector('input[name=sdt-updt]').value = providerinfo.sdt;
    document.querySelector('input[name=diachi-updt]').value = providerinfo.diachi;
    document.querySelector('input[name=trangthai-updt]').value = providerinfo.trangthai

    LoadStatus()
}

document.querySelector('input[name=sdt-updt]').addEventListener('input', ()=>{
    const sdt = document.querySelector('input[name=sdt-updt]').value;
    if(sdt.length == 11) {
        const sdt_temp = sdt.slice(0, 10)
        document.querySelector('input[name=sdt-updt]').value = sdt_temp
    }
})

const btn_update = document.getElementById('update');

btn_update.addEventListener('click', async()=>{
    const url = '../Controller/Provider/UpdateProvider.php';

    let id = document.querySelector('p[name=maNCC]').innerHTML.toString();
    //Chuẩn bị data để update
    const idncc = parseInt(id.slice(0, id.length));
    
    const tenmoi = document.querySelector('input[name=name-updt]').value;
    const sdtmoi = document.querySelector('input[name=sdt-updt]').value;
    const diachimoi = document.querySelector('input[name=diachi-updt]').value;

    const UpdateProvider = async()=>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idNCC: idncc,
                    ten: tenmoi,
                    sdt: sdtmoi,
                    diachi: diachimoi
                })
            });
    
            const data = response.json();
            return data;
        } catch (error) {
            console.error(error);
        }
    }

    const message = await UpdateProvider();
    alert(message.message);

    LoadProviders('');
}) 
//Cập nhật 

//Thêm nhà cung cấp
const confirmAdd = document.getElementById('add');

document.getElementById("add-ncc").addEventListener('click', () =>{
    modal.classList.add("open-modal");
    formAddNCC.classList.add("open-add");
    document.querySelector(".hidden-log-out").classList.add("active");
})

document.getElementById("close-add").addEventListener('click', async() =>{
    modal.classList.remove("open-modal");
    formAddNCC.classList.remove("open-add");
    document.querySelector(".hidden-log-out").classList.remove("active");
    ClearAdd();
})

document.querySelector('input[name=sdt-add]').addEventListener('input', ()=>{
    const sdt = document.querySelector('input[name=sdt-add]').value;
    if(sdt.length == 11) {
        const sdt_temp = sdt.slice(0, 10)
        document.querySelector('input[name=sdt-add]').value = sdt_temp
    }
})

confirmAdd.addEventListener('click', async()=>{
    const ten = document.querySelector('input[name=name-add]').value;
    const sdt = document.querySelector('input[name=sdt-add]').value;
    const diachi = document.querySelector('input[name=diachi-add]').value;

    if(ten.length == 0) {
        alert("Chưa nhập tên kìa ní !"); 
        return;
    }
    
    if(sdt.length != 10 || sdt.slice(0, 1) != '0') {
        alert("Số địn họi chưa hộp lệ (Yêu cầu 10 số và số đầu bằng 0)!"); 
        return;
    }
    if(diachi.length == 0) {
        alert("Chưa nhập địa chỉ kìa ní !"); 
        return;
    }
    
    const url = '../Controller/Provider/AddProvider.php';
    const AddProvider = async() =>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ten: ten,
                    sdt: sdt,
                    diachi: diachi
                })
            });

            const data = response.json();
            return data;
        } catch (error) {
            console.log(error);
        }
    }

    const message = await AddProvider();
    alert(message.message);
    ClearAdd()
    modal.classList.remove("open-modal");
    formAddNCC.classList.remove("open-add");
    document.querySelector(".hidden-log-out").classList.remove("active"); 
    LoadProviders('');

})

ClearAdd = ()=>{
    document.querySelector('input[name=name-add]').value = '';
    document.querySelector('input[name=sdt-add]').value = '';
    document.querySelector('input[name=diachi-add]').value = '';  
}
//Thêm nhà cung cấp

//Khóa
const btn = document.getElementById('re-btn')
Handle = async()=>{
    const text = btn.innerHTML
    let id = document.querySelector('p[name=maNCC]').innerHTML.toString()
    const idncc = id.slice(0, id.length)
    
    if(text=="Khóa") {
        if(!confirm("Bà chắc chưa bà Thơ ???")) return
    }
    
    const url = '../Controller/Provider/HandleProvider.php'
    const HandleProvider = async()=>{
        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    idNCC: idncc,
                    text: text
                })
            })

            const data = response.json()
            return data
        } catch (error) {
            console.error(error)
        }
    }

    const result = await HandleProvider()
    alert(result.message) //Thông báo
    //Set lại nút handle
    document.querySelector('input[name=trangthai-updt]').value = result.trangthaimoi
    LoadStatus()
}

LoadStatus = ()=>{
    var trangthai = document.querySelector('input[name=trangthai-updt]').value
    console.log(trangthai)
    btn.innerText = parseInt(trangthai) == 1? "Khóa" : "Mở khóa"
}
//Khóa
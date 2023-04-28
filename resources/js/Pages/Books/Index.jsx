import AppLayout from "@/Layouts/AppLayout";
import {Link, router} from "@inertiajs/react";
import {useState} from "react";

export default function Index(props){
    const edit=props.can.edit;
    const [filter, setFilter]=useState({
        name:props.filter.name
    });
    const handleFilter=()=>{
        router.post(route("books.filter"),filter);
    }
    const handleChange=(event)=>{
        setFilter({
            ...filter,
            [event.target.id]:event.target.value
        });
    }

    const bookList = [];
    props.books.forEach((book)=>{
        bookList.push(<tr key={book.id}>
            <td>{book.name}</td>
            <td>{book.summary}</td>
            <td>{book.isbn}</td>
            <td>{book.picture && <img alt="foto" width="80px" src={"/storage/books/"+book.picture} />}</td>
            <td>{book.page}</td>
            <td>{book.category.name}</td>
            <td>
                {edit && <Link className="btn btn-primary" href={ route('books.edit', book.id)}>Redaguoti</Link>}
                {edit && <button className="btn btn-warning" onClick={()=>{router.delete(route("books.destroy", book.id))}}>Trinti</button>}
            </td>
        </tr> )
    });
    return(
        <AppLayout>
            <div className="card">
                <div className="card-header">
                    Knygų sąrašas
                    {edit && <Link className="btn btn-info float-end" href={route("books.create")}>Pridėti knygą</Link>}

                </div>
                <div className="card-body">
                    <table className="table">
                        <thead>
                        <tr>
                            <th>
                                <label>Pgal knygos pavadinimą</label>
                                <input onChange={handleChange} id="name" value={filter.name} type="text" className="form-control"/>
                            </th>
                            <th>
                                <button onClick={handleFilter} className="btn btn-warning">Ieskoti</button>
                            </th>
                        </tr>
                            <tr>
                                <th>Pavadinimas</th>
                                <th>Santrauka</th>
                                <th>ISBN</th>
                                <th>Nuotrauka</th>
                                <th>Puslapių kiekis</th>
                                <th>Kategorija</th>
                                {edit &&<th>Veiksmai</th>}
                            </tr>
                        </thead>
                        <tbody>
                            {bookList}
                        </tbody>
                    </table>
                </div>
            </div>
        </AppLayout>
    )
}

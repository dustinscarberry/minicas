import { useState } from 'react';
import classnames from 'classnames';

const Table = ({headers, data, searchable, sortable}) => {
  const [search, setSearch] = useState('');
  const [sortColumn, setSortColumn] = useState(undefined);
  const [sortDirection, setSortDirection] = useState('desc');

  const handleTableSearch = (e) => {
    setSearch(e.target.value);
  }

  const handleTableSort = (column) => {
    setSortColumn(column);
    setSortDirection(sortDirection == 'asc' ? 'desc' : 'asc');
  }

  const filterAndSortData = (data) => {
    let filteredAndSortedData = [...data];

    // search filter
    if (searchable && search != '') {
      filteredAndSortedData = filteredAndSortedData.filter(item => { 
        return item.rowData.some((s, index) => {
          // bypass search for non searchable columns
          if ('searchable' in headers[index] && headers[index].searchable == false)
            return false;
      
          // use search value if specified
          if ('searchValue' in s)
            return s.searchValue.includes(search);
          
          // search on base data
          return s.value.includes(search);
        });
      });
    }

    // sort filter
    if (sortable && sortColumn != undefined) {
      filteredAndSortedData.sort(function(a, b) {
        const aValue = 'sortValue' in a.rowData[sortColumn]
          ? a.rowData[sortColumn].sortValue
          : a.rowData[sortColumn].value;
        const bValue = 'sortValue' in b.rowData[sortColumn]
          ? b.rowData[sortColumn].sortValue
          : b.rowData[sortColumn].value;

        if (aValue < bValue) {
          if (sortDirection == 'asc') return -1;
          if (sortDirection == 'desc') return 1;
        }
        else {
          if (sortDirection == 'asc') return 1;
          if (sortDirection == 'desc') return -1; 
        }
      });
    }

    return filteredAndSortedData;
  }

  const filteredAndSortedData = filterAndSortData(data);

  return <div className="react-table">
    <div className="table-toolbar">
      {searchable &&
        <div className="search-box">
          <label>Search:</label>
          <input type="text" name="search" value={search} onChange={handleTableSearch}/>
        </div>
      }
    </div>
    <table className="table-simple">
      <thead>
        <tr>
          {headers.map((header, i) => {
            return <th key={i} width={('width' in header ? header.width : undefined)}>
              {header.title}
              {sortable && ('sortable' in header && header.sortable != false || !('sortable' in header)) &&
                <button
                  className={
                    classnames(
                      'sort-handle',
                      {
                        'is-active': i == sortColumn,
                        'sort-asc': sortDirection == 'asc' && i == sortColumn,
                        'sort-desc': sortDirection == 'desc' && i == sortColumn
                      }
                    )
                  }
                  onClick={() => handleTableSort(i)}
                ></button>
              }
            </th>
          })}
        </tr>
      </thead>
      <tbody>
        {filteredAndSortedData.map((row, i) => {    
          return <tr key={i}>
            {row.rowData.map((item, j) => {
              return <td key={j}>{item.value}</td>
            })}
          </tr>
        })}
      </tbody>
    </table>
  </div>
}

Table.defaultProps = {
  headers: undefined,
  data: undefined,
  searchable: false,
  sortable: false
}

export default Table;
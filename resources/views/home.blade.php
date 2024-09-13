<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Futuristic Inventory Management</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
            color: #60a5fa;
            font-size: 2.5em;
            margin-bottom: 30px;
        }
        .add-item-form {
            background-color: #1e293b;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            border: 1px solid #4f46e5;
        }
        .add-item-form h2 {
            color: #a78bfa;
            margin-top: 0;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #4b5563;
            background-color: #374151;
            color: #e2e8f0;
            border-radius: 4px;
        }
        button {
            background-color: #8b5cf6;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #7c3aed;
        }
        .item-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .item-card {
            background-color: #1e293b;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 15px;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .item-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.2);
        }
        .item-card h3 {
            color: #93c5fd;
            margin-top: 0;
        }
        .item-card p {
            margin: 5px 0;
        }
        .price {
            color: #fcd34d;
            font-weight: bold;
        }
        .inventory-table {
            width: 100%;
            border-collapse: collapse;
            background-color: #1e293b;
            border-radius: 8px;
            overflow: hidden;
        }
        .inventory-table th, .inventory-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #4b5563;
        }
        .inventory-table th {
            background-color: #2d3748;
            color: #60a5fa;
        }
        .inventory-table tr:hover {
            background-color: #2d3748;
        }
        .action-buttons button {
            margin-right: 5px;
        }
        .edit-btn {
            background-color: #eab308;
        }
        .edit-btn:hover {
            background-color: #ca8a04;
        }
        .delete-btn {
            background-color: #ef4444;
        }
        .delete-btn:hover {
            background-color: #dc2626;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #0f172a;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <h1>Futuristic Inventory Management</h1>

        {{-- Add item part --}}
        <form class="add-item-form" method="POST" action="{{url('/add_Items')}}">
            <h2>Add New Item</h2>
            <div class="form-grid">
                @csrf
                <input type="text" name="item_name" placeholder="Name" required>
                <input type="text" name="description" placeholder="Description" required>
                <input type="number" name="quantity" placeholder="Quantity" required>
                <input type="number" step="0.01" name="price" placeholder="Price" required>
            </div>
            <button type="submit" style="margin-top: 15px;">Add Item</button>
        </form>
        
        {{-- Data display --}}
        <div class="item-grid">
            @foreach ($data as $item)
            @if($item)
            <div class="item-card">
                <h3>{{ $item->item_name }}</h3>
                <p>{{ $item->description }}</p>
                <p>{{ $item->quantity }}</p>
                <p class="price">${{ $item->price }}</p>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Items Table -->
        <table class="inventory-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Data display --}}
                @foreach ($data as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ $item->price }}</td>
                    <td class="action-buttons">
                        {{-- Data Delete  --}}
                        <form action="{{ url('/items/' . $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button class="delete-btn">Delete</button>
                        </form>
                        {{-- Data Update button  --}}
                        <button class="edit-btn" data-id="{{ $item->id }}" data-name="{{ $item->item_name }}" data-description="{{ $item->description }}" data-quantity="{{ $item->quantity }}" data-price="{{ $item->price }}">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Data Update process --}}
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Item</h2>
            <form id="editForm" action="{{ url('/items/' . $item->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid">
                    <input type="text" id="editItemName" name="item_name" value="{{ $item->item_name }}" required>
                    <input type="text" id="editDescription" name="description" value="{{ $item->description }}" required>
                    <input type="number" id="editQuantity" name="quantity"  value="{{ $item->quantity }}" required>
                    <input type="number" id="editPrice" name="price" step="0.01" value="{{ $item->price }}" required>
                </div>
                <button type="submit" style="margin-top: 15px;">Update Item</button>
            </form>
        </div>
    </div>

    {{-- POP UP Window Js --}}
    <script>
        
        const modal = document.getElementById("editModal");
        const closeModal = document.querySelector(".close");

        
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.getAttribute('data-id');
                const itemName = this.getAttribute('data-name');
                const itemDescription = this.getAttribute('data-description');
                const itemQuantity = this.getAttribute('data-quantity');
                const itemPrice = this.getAttribute('data-price');

                
                const formAction = "{{ url('/items') }}/" + itemId;
                document.getElementById('editForm').action = formAction;

                
                document.getElementById('editItemName').value = itemName;
                document.getElementById('editDescription').value = itemDescription;
                document.getElementById('editQuantity').value = itemQuantity;
                document.getElementById('editPrice').value = itemPrice;

                
                modal.style.display = "block";
            });
        });

        
        closeModal.onclick = function() {
            modal.style.display = "none";
        };

        
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        };
    </script>
</body>

</html>
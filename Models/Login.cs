using System.ComponentModel.DataAnnotations;

namespace Models;

public class Login
{
    [Required(ErrorMessage = "Username is Required")]
    public string? Username{get;set;}

    [Required(ErrorMessage ="Password is required")]
    [DataType(DataType.Password)]
    public string? Password{get;set;}
    
}
using System;
					
public class Program
{
	/* Simplified version for C# based on Wouter Bokslag & Jason F. - https://github.com/prototux - work */

	public static Int32 transform(int data, int[] sec) {
		Int32 result = ((data % sec[0]) * sec[2]) - ((data / sec[0]) * sec[1]);
		if (result < 0)
			result += (sec[0] * sec[2]) + sec[1];
		return result;
	}
	
	public static string getKey(string seedTXT, string appKeyTXT) {
		Int32 result;
		
		string[] seed = {seedTXT.Substring(0,2), seedTXT.Substring(2,2), seedTXT.Substring(4,2), seedTXT.Substring(6,2)};
		string[] appKey = {appKeyTXT.Substring(0,2), appKeyTXT.Substring(2,2)};

		// Hardcoded secrets
		int[] sec_1 = {0xB2, 0x3F, 0xAA};
		int[] sec_2 = {0xB1, 0x02, 0xAB};
		
		// Compute each 16b part of the response, with the twist, and return it
		Int32 res_msb = transform(Int16.Parse(appKey[0]+appKey[1], System.Globalization.NumberStyles.HexNumber), sec_1) | transform(Int16.Parse(seed[0]+seed[3], System.Globalization.NumberStyles.HexNumber), sec_2);
		Int32 res_lsb = transform(Int16.Parse(seed[1]+seed[2], System.Globalization.NumberStyles.HexNumber), sec_1) | transform(res_msb, sec_2);
		result = (res_msb << 16) | res_lsb;
		return result.ToString("X8");
	}
	
	public static void Main()
	{
		Console.WriteLine(getKey("11111111", "D91C"));
	}
}